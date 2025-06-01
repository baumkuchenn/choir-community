<?php

namespace App\Http\Controllers;

use App\Models\Choir;
use App\Models\Concert;
use App\Models\Donation;
use App\Models\Event;
use App\Models\Feedback;
use App\Models\Kupon;
use App\Models\Member;
use App\Models\Panitia;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\TicketType;
use App\Notifications\BeliTiketNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

use function Laravel\Prompts\table;

class EticketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $konserDekat = Event::with(['concert', 'choirs'])
            ->whereHas('concert', function ($query) {
                $query->where('status', 'published')
                    ->whereHas('ticketTypes', function ($ticketQuery) {
                        $ticketQuery->where('pembelian_terakhir', '>', Carbon::now())
                            ->where('visibility', 'public');
                    });
            })
            ->whereHas('choirs', function ($query) {
                $query->where('penyelenggara', 'ya');
            })
            ->orderBy('tanggal_mulai', 'asc')
            ->get()
            ->map(function ($event) {
                $event->choir = $event->choirs->first();
                unset($event->choirs);
                return $event;
            });

        // Append "hargaMulai" to each concert
        foreach ($konserDekat as $konser) {
            $hargaMulai = TicketType::where('concerts_id', $konser->concert->id ?? null)
                ->where('visibility', 'public')
                ->min('harga');
            $konser->hargaMulai = number_format($hargaMulai, 0, ',', '.');
        }

        //Algoritma rekomendasi
        function preprocessCity($lokasi)
        {
            return collect(explode(',', $lokasi))
                ->map(fn($city) => Str::of($city)->lower()->trim()->__toString())
                ->filter();
        }

        function getTermsFromEvent($event): array
        {
            $terms = [];

            foreach ($event->choirs as $choir) {
                if ($choir->pivot->penyelenggara === 'ya') {
                    $terms[] = "choir_{$choir->id}";
                }
            }

            foreach (preprocessCity($event->lokasi) as $city) {
                $terms[] = "city_{$city}";
            }

            return $terms;
        }

        function tfidfVectors(Collection $documents): array
        {
            $termFreqs = []; // TF(t, d): raw count
            $docFreqs = [];  // df(t): number of documents with term t

            // Step 1: Count term frequency and document frequency
            foreach ($documents as $docId => $terms) {
                $counts = array_count_values($terms); // raw counts

                foreach ($counts as $term => $count) {
                    $termFreqs[$docId][$term] = $count;

                    // Count DF only once per document
                    $docFreqs[$term] = ($docFreqs[$term] ?? 0) + 1;
                }
            }

            $N = count($documents); // total number of documents
            $idf = [];

            // Step 2: Compute IDF
            foreach ($docFreqs as $term => $df) {
                $idf[$term] = log($N / $df) + 1; // log base e
            }
            $vectors = [];

            // Step 3: Compute TF-IDF using raw TF
            foreach ($termFreqs as $docId => $tfList) {
                foreach ($tfList as $term => $tf) {
                    $vectors[$docId][$term] = $tf * $idf[$term];
                }
            }

            return [$vectors, $idf];
        }


        function cosineSimilarity($vec1, $vec2)
        {
            $intersection = array_intersect_key($vec1, $vec2);

            $dotProduct = 0;
            foreach ($intersection as $key => $_) {
                $dotProduct += $vec1[$key] * $vec2[$key];
            }

            $normA = sqrt(array_sum(array_map(fn($v) => $v ** 2, $vec1)));
            $normB = sqrt(array_sum(array_map(fn($v) => $v ** 2, $vec2)));

            if ($normA == 0 || $normB == 0) return 0;

            return $dotProduct / ($normA * $normB);
        }

        if (!auth()->check()) {
            $recomEvents = collect();
        } else {
            // Step 1: Get user profile events
            $purchasedConcerts = Purchase::where('users_id', auth()->id())
                ->whereIn('status', ['verifikasi', 'selesai'])
                ->with('invoice.tickets.ticket_type.concert.event.choirs')
                ->get()
                ->pluck('invoice.tickets')
                ->flatten()
                ->pluck('ticket_type.concert')
                ->unique()
                ->filter();

            $userProfileTerms = [];

            foreach ($purchasedConcerts as $concert) {
                $event = $concert->event;
                if ($event) {
                    $userProfileTerms = array_merge($userProfileTerms, getTermsFromEvent($event));
                }
            }

            if (empty($userProfileTerms)) {
                $user = auth()->user();
                $userProfileTerms = preprocessCity($user->kota ?? '')->map(fn($city) => "city_{$city}")->toArray();
            }

            // Step 2: Fetch all candidate events
            $events = Event::with(['concert', 'choirs'])->whereHas('concert', function ($query) {
                $query->where('status', 'published')
                    ->whereHas('ticketTypes', fn($q) => $q->where('pembelian_terakhir', '>', now())->where('visibility', 'public'));
            })->get();

            // Step 3: Build term sets
            $docTerms = [];
            foreach ($events as $i => $event) {
                $docTerms[$i] = getTermsFromEvent($event);
            }

            // Add user profile as a pseudo-document
            $docTerms['user'] = $userProfileTerms;

            // Step 4: Build TF-IDF vectors
            [$tfidfVectors, $idf] = tfidfVectors(collect($docTerms));

            $userVector = $tfidfVectors['user'];
            unset($tfidfVectors['user']);

            // Step 5: Score each event
            $recomEvents = $events
                ->map(function ($event, $index) use ($tfidfVectors, $userVector) {
                    $event->similarity_score = cosineSimilarity($tfidfVectors[$index] ?? [], $userVector);
                    return $event;
                })
                ->sortByDesc('similarity_score')->values();

            $minSimilarity = 0.1;
            $recomEvents = $recomEvents->filter(fn($event) => $event->similarity_score >= $minSimilarity);

            // Step 6: Add prices (optional)
            foreach ($recomEvents as $event) {
                $event->choir = $event->choirs->first();
                $event->hargaMulai = number_format(
                    TicketType::where('concerts_id', $event->concert->id ?? null)
                        ->where('visibility', 'public')
                        ->min('harga'),
                    0,
                    ',',
                    '.'
                );
            }
        }

        $penyelenggara = Choir::select('choirs.id', 'choirs.nama', 'choirs.logo')
            ->withSum(['events as total_tickets_sold' => function ($eventQuery) {
                $eventQuery->where('penyelenggara', 'ya')
                    ->whereHas('concert', function ($concertQuery) {
                        $concertQuery->where('status', 'published');
                    })
                    ->join('concerts', 'events.id', '=', 'concerts.events_id')
                    ->join('ticket_types', 'concerts.id', '=', 'ticket_types.concerts_id')
                    ->join('purchase_details', 'ticket_types.id', '=', 'purchase_details.ticket_types_id')
                    ->join('purchases', 'purchases.id', '=', 'purchase_details.purchases_id')
                    ->whereIn('purchases.status', ['verifikasi', 'selesai']);
            }], 'purchase_details.jumlah')
            ->get()
            ->filter(fn($choir) => $choir->total_tickets_sold !== null)
            ->sortByDesc('total_tickets_sold')
            ->values()
            ->take(10);

        $purchases = null;
        if (Auth::check()) {
            $user = Auth::user();
            $purchases = Purchase::with(['concert.event'])
                ->where('users_id', $user->id)
                ->where('status', 'bayar')
                ->withCount('ticketTypes as jumlah_tiket')
                ->get();
        }

        return view('eticketing.index', compact('konserDekat', 'recomEvents', 'penyelenggara', 'purchases'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $concert = Concert::with('event.choirs', 'ticketTypes')
            ->where('id', $id)
            ->firstOrFail();

        $event = $concert->event;

        $event->penyelenggara = $event->choirs->first()->nama;
        $event->logo = $event->choirs->first()->logo;

        $tickets = $concert->ticketTypes->where('visibility', 'public');
        $hargaMulai = $tickets->min('harga');

        return view('eticketing.show', compact('concert', 'event', 'tickets', 'hargaMulai'));
    }

    public function showChoir(string $id, Request $request)
    {
        $choir = Choir::find($id);
        $tab = $request->tab ?? 'berlangsung';

        $konserBerlangsung = collect();
        $konserLalu = collect();

        if ($tab == 'berlangsung') {
            $konserBerlangsung = Event::with('concert')
                ->whereHas('concert', function ($query) {
                    $query->where('status', 'published')
                        ->whereHas('ticketTypes', function ($ticketQuery) {
                            $ticketQuery->where('pembelian_terakhir', '>', Carbon::now())
                                ->where('visibility', 'public');
                        });
                })
                ->whereHas('choirs', function ($query) use ($id) {
                    $query->where('choirs.id', $id);
                })
                ->latest()
                ->get();

            foreach ($konserBerlangsung as $konser) {
                $hargaMulai = TicketType::where('concerts_id', $konser->concert->id ?? null)
                    ->where('visibility', 'public')
                    ->min('harga');
                $konser->hargaMulai = number_format($hargaMulai, 0, ',', '.');
            }
        } elseif ($tab == 'lalu') {
            $konserLalu = Event::with('concert')
                ->whereHas('concert', function ($query) {
                    $query->where('status', 'published')
                        ->whereHas('ticketTypes', function ($ticketQuery) {
                            $ticketQuery->where('pembelian_terakhir', '<', Carbon::now())
                                ->where('visibility', 'public');
                        });
                })
                ->whereHas('choirs', function ($query) use ($id) {
                    $query->where('choirs.id', $id);
                })
                ->latest()
                ->get();
            foreach ($konserLalu as $konser) {
                $hargaMulai = TicketType::where('concerts_id', $konser->concert->id ?? null)
                    ->where('visibility', 'public')
                    ->min('harga');
                $konser->hargaMulai = number_format($hargaMulai, 0, ',', '.');
            }
        }

        return view('eticketing.show-choir', compact('choir', 'tab', 'konserBerlangsung', 'konserLalu'));
    }

    public function kupon(string $id)
    {
        $event = Event::find($id);
        $kupons = $event->concert->kupons
            ->where('tipe', 'kupon')
            ->filter(function ($kupon) {
                $alreadyUsed = $kupon->usedAsKupon()
                    ->where('users_id', Auth::id())
                    ->whereIn('status', ['bayar', 'verifikasi', 'selesai']) // or just 'selesai'
                    ->exists();
                if (!$alreadyUsed) {
                    $usedCount = $kupon->usedAsKupon()
                        ->where('status', 'selesai')
                        ->count();
                    return $usedCount < $kupon->jumlah;
                }
            });
        return view('eticketing.modal.kupon.form-add', compact('kupons'));
    }

    public function order(Request $request, string $id)
    {
        $tiketDipilih = json_decode($request->input('tickets'), true);

        if (!$tiketDipilih || empty($tiketDipilih)) {
            return redirect()->back()->with('error', 'Pilih minimal satu tiket untuk melanjutkan.');
        }

        $concert = Concert::with('event.choirs', 'ticketTypes')
            ->where('id', $id)
            ->firstOrFail();

        $event = $concert->event;

        $event->penyelenggara = $event->choirs->first()->nama;
        $event->logo = $event->choirs->first()->logo;

        return view('eticketing.order', compact('event', 'tiketDipilih'));
    }

    public function purchase(Request $request, string $id)
    {
        $user = Auth::user();

        if ($request->input('purchase-menu') === 'purchase') {
            $tiketDipilih = collect($request->input('tickets', []))->map(fn($t) => json_decode($t, true));

            $totalHarga = $tiketDipilih->sum(fn($t) => $t['jumlah'] * $t['harga']);
            $totalHarga = $totalHarga - $request->discount_amount;

            $referal = null;
            if ($request->referals_kode) {
                $referal = Kupon::where('kode', $request->referals_kode)->first();
            }

            $purchase = Purchase::where('users_id', $user->id)
                ->where('concerts_id', $id)
                ->where('created_at', '>=', now()->subMinutes(10))
                ->first();

            if (!$purchase) {
                $purchase = Purchase::create([
                    'status' => 'bayar',
                    'total_tagihan' => $totalHarga,
                    'kupons_id' => $request->kupons_id ?? null,
                    'referals_id' => isset($referal) ? $referal->id : null,
                    'users_id' => $user->id,
                    'concerts_id' => $id,
                ]);
                //Bisa ambil waktu_pembelian
                $purchase->refresh();

                foreach ($tiketDipilih as $tiket) {
                    PurchaseDetail::create([
                        'purchases_id' => $purchase->id,
                        'ticket_types_id' => $tiket['id'],
                        'jumlah' => $tiket['jumlah'],
                    ]);
                }
            }
        } else {
            $purchase = Purchase::findOrFail($id);

            $tiketDipilih = $purchase->ticketTypes()
                ->get()
                ->map(fn($detail) => [
                    'id' => (string) $detail->id,
                    'nama' => (string) $detail->nama,
                    'harga' => (string) $detail->harga,
                    'jumlah' => (int) $detail->pivot->jumlah,
                ])
                ->toArray();

            $concertId = $purchase->concerts_id;
        }

        // Expiration check
        $expiredAt = Carbon::parse($purchase->waktu_pembelian)->addHours(24);
        $isExpired = now()->greaterThan($expiredAt);

        if ($isExpired && $purchase->status === 'bayar') {
            $purchase->update(['status' => 'batal']);
        }

        $concert = Concert::with(['event', 'bank'])
            ->where('id', $concertId ?? $id)
            ->firstOrFail();

        return view('eticketing.purchase', compact('tiketDipilih', 'concert', 'purchase'));
    }

    public function payment(Request $request, string $id)
    {
        if ($request->input('payment-menu') === 'payment') {
            $request->validate([
                'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $image = $request->file('bukti_pembayaran');
            $filename = time() . '_purchaseId_' . $id . '.jpg';
            $path = $image->storeAs('bukti_pembayaran', $filename, 'public');

            $purchases = Purchase::find($id);
            $purchases->update([
                'status' => 'verifikasi',
                'gambar_pembayaran' => $path,
                'waktu_pembayaran' => now(),
            ]);

            $purchases->refresh();

            //Notifikasi ke admin
            $concert = Concert::with('event.choirs')->find($purchases->concert->id);
            $choir = $concert->event->choirs->first();
            $adminUsers = Member::with('user')
                ->where('choirs_id', $choir->id)
                ->where('admin', 'ya')
                ->get()
                ->pluck('user')
                ->filter()
                ->unique('id');

            $eticketMembers = Member::with(['user', 'position'])
                ->where('choirs_id', $choir->id)
                ->whereHas('position', fn($q) => $q->where('akses_eticket', '1'))
                ->get()
                ->pluck('user')
                ->filter()
                ->unique('id');

            $eticketPanitia = Panitia::with(['user', 'jabatan'])
                ->where('events_id', $concert->events_id)
                ->whereHas('jabatan', fn($q) => $q->where('akses_eticket', '1'))
                ->get()
                ->pluck('user')
                ->filter()
                ->unique('id');

            $notifiableUsers = $adminUsers
                ->merge($eticketMembers)
                ->merge($eticketPanitia)
                ->unique('id');

            Notification::send($notifiableUsers, new BeliTiketNotification($concert));
        }

        $purchases = Purchase::with(['concert', 'invoice:id,purchases_id,kode'])
            ->where('id', $id)
            ->first();

        $tiketDibeli = TicketType::whereHas('purchases', function ($query) use ($id) {
            $query->where('purchases.id', $id);
        })
            ->with(['purchases' => function ($query) use ($id) {
                $query->where('purchases.id', $id);
            }])
            ->get()
            ->map(fn($item) => [
                'id' => (string) $item->id,
                'nama' => (string) $item->nama,
                'harga' => (string) $item->harga,
                'jumlah' => (int) $item->purchases->first()->pivot->jumlah,
            ])
            ->toArray();

        return view('eticketing.payment-proof', compact('tiketDibeli', 'purchases'));
    }

    public function myticket()
    {
        $user = Auth::user();

        $purchaseBerlangsung = Purchase::with([
            'concert.event.choirs',
            'ticketTypes',
            'invoice.tickets',
            'concert.feedbacks'
        ])
            ->where('users_id', $user->id)
            ->whereHas('concert.event', function ($query) {
                $query->whereRaw("TIMESTAMP(tanggal_selesai, jam_selesai) > ?", [now()]);
            })
            ->whereHas('concert.event.choirs', function ($query) {
                $query->where('penyelenggara', 'ya');
            })
            ->whereNotIn('status', ['batal', 'bayar'])
            ->get()
            ->sortByDesc(fn($purchase) => $purchase->concert->event->tanggal_mulai)
            ->map(function ($purchase) {
                $purchase->penyelenggara = $purchase->concert->event->choirs->first()->nama;
                $purchase->logo = $purchase->concert->event->choirs->first()->logo;
                $purchase->jumlah_tiket = $purchase->ticketTypes->pluck('pivot.jumlah')->sum();
                $purchase->check_in = $purchase->invoice?->tickets?->where('check_in', 'ya')->isNotEmpty() ? 'ya' : 'tidak';
                $purchase->feedbacks = $purchase->concert->feedbacks->where('users_id', $purchase->users_id)->isNotEmpty() ? 'sudah' : 'belum';
                return $purchase;
            });
        $purchaseLalu = Purchase::with([
            'concert.event.choirs',
            'ticketTypes',
            'invoice.tickets',
            'concert.feedbacks'
        ])
            ->where('users_id', $user->id)
            ->where(function ($query) {
                $query->whereHas(
                    'concert.event',
                    fn($subQuery) =>
                    $subQuery->whereRaw("TIMESTAMP(tanggal_selesai, jam_selesai) < ?", [now()])
                )
                    ->orWhere('status', 'batal');
            })
            ->whereHas('concert.event.choirs', function ($query) {
                $query->where('penyelenggara', 'ya');
            })
            ->whereNotIn('status', ['bayar'])
            ->get()
            ->sortByDesc(fn($purchase) => $purchase->concert->event->tanggal_mulai)
            ->map(function ($purchase) {
                $purchase->penyelenggara = $purchase->concert->event->choirs->first()->nama;
                $purchase->logo = $purchase->concert->event->choirs->first()->logo;
                $purchase->jumlah_tiket = $purchase->ticketTypes->pluck('pivot.jumlah')->sum();
                $purchase->check_in = $purchase->invoice?->tickets?->where('check_in', 'ya')->isNotEmpty() ? 'ya' : 'tidak';
                $purchase->feedbacks = $purchase->concert->feedbacks->where('users_id', $purchase->users_id)->isNotEmpty() ? 'sudah' : 'belum';

                return $purchase;
            });
        return view('eticketing.myticket', compact('purchaseBerlangsung', 'purchaseLalu'));
    }

    public function invoice(string $id)
    {
        $user = Auth::user()->name;

        $purchase = Purchase::with([
            'concert.event.choirs',
            'invoice.tickets.ticket_type',
            'ticketTypes'
        ])
            ->whereHas('concert.event.choirs', function ($query) {
                $query->where('penyelenggara', 'ya');
            })
            ->findOrFail($id);

        $concerts = $purchase->concert;
        $events = $purchase->concert->event;
        $events->penyelenggara = $events->choirs->first()->nama;
        $events->logo = $events->choirs->first()->logo;

        $invoices = $purchase->invoice;
        $tickets = $purchase->invoice->tickets;
        $purchaseDetail = $purchase->ticketTypes;

        return view('eticketing.invoice', compact('user', 'purchase', 'concerts', 'events', 'invoices', 'tickets', 'purchaseDetail'));
    }

    public function ticket(string $id)
    {
        $user = Auth::user()->name;

        $purchase = Purchase::with([
            'concert.event.choirs',
            'invoice.tickets.ticket_type',
            'ticketTypes'
        ])
            ->whereHas('concert.event.choirs', function ($query) {
                $query->where('penyelenggara', 'ya');
            })
            ->findOrFail($id);

        $tickets = $purchase->invoice->tickets;

        return view('eticketing.tickets', compact('user', 'purchase', 'tickets'));
    }

    public function feedback(Request $request, string $id)
    {
        // Retrieve Event Details with Eloquent Relationships
        $purchase = Purchase::with([
            'concert.event.choirs',
            'concert.bank',
        ])
            ->findOrFail($id);

        $concert = $purchase->concert;
        $event = $purchase->concert->event;
        $event->penyelenggara = $event->choirs->first()->nama;
        $event->logo = $event->choirs->first()->logo;

        return view('eticketing.feedback', compact('purchase', 'event', 'concert'));
    }

    public function saveFeedback(Request $request, string $id)
    {
        $user = Auth::user();

        // Validate Feedback Input
        $request->validate([
            'feedback' => 'required|string|max:1000',
            'gambar-feedback' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Get Concert ID using Eloquent
        $purchase = Purchase::findOrFail($id);
        $concertId = $purchase->concerts_id;

        // Handle Donation
        if ($request->input('donasi') === 'ya') {
            $request->validate([
                'nama' => [
                    'nullable',
                    'string',
                    'max:255',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($value && !$request->filled('jumlah')) {
                            $fail('Jumlah harus diisi jika nama diisi.');
                        }
                    },
                ],
                'nominal' => [
                    'nullable',
                    'numeric',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($value && !$request->filled('nama')) {
                            $fail('Nama harus diisi jika jumlah diisi.');
                        }
                    },
                ],
            ]);
            if ($request->filled('nama') && $request->filled('jumlah')) {
                Donation::create([
                    'nama' => $request->input('nama'),
                    'jumlah' => $request->input('jumlah'),
                    'concerts_id' => $concertId,
                    'users_id' => $user->id,
                ]);
            }
        }

        // Prepare Feedback Data
        $feedbackData = [
            'isi' => $request->input('feedback'),
            'concerts_id' => $concertId,
            'users_id' => $user->id,
        ];

        // Handle Image Upload
        if ($request->hasFile('gambar-feedback')) {
            $image = $request->file('gambar-feedback');
            $filename = 'feedback_purchaseId_' . $id . '.' . $image->extension();
            $path = $image->storeAs('feedback', $filename, 'public');
            $feedbackData['gambar'] = $path;
        }

        // Save Feedback
        Feedback::create($feedbackData);

        return redirect()->route('eticket.myticket')->with([
            'status' => 'success',
            'message' => 'Feedback berhasil dikirim!'
        ]);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('search_input');
        $tab = $request->input('tab');

        $events = collect();
        $choirs = collect();

        if ($tab === 'events') {
            $events = Event::with(['concert', 'choirs'])
                ->whereHas('concert', function ($query) {
                    $query->where('status', 'published')
                        ->whereHas('ticketTypes', function ($ticketQuery) {
                            $ticketQuery->where('pembelian_terakhir', '>', Carbon::now())
                                ->where('visibility', 'public');
                        });
                })
                ->whereHas('choirs', function ($query) {
                    $query->where('penyelenggara', 'ya');
                })
                ->where(function ($query) use ($keyword) {
                    $query->where('nama', 'like', "%$keyword%")
                        ->orWhereHas('choirs', function ($choirQuery) use ($keyword) {
                            $choirQuery->where('nama', 'like', "%$keyword%");
                        });
                })
                ->latest()
                ->get()
                ->map(function ($event) {
                    $event->choir = $event->choirs->first();
                    unset($event->choirs);
                    return $event;
                });

            // Append "hargaMulai" to each concert
            foreach ($events as $konser) {
                $hargaMulai = TicketType::where('concerts_id', $konser->concert->id ?? null)
                    ->where('visibility', 'public')
                    ->min('harga');
                $konser->hargaMulai = number_format($hargaMulai, 0, ',', '.');
            }
        } elseif ($tab === 'choirs') {
            $choirs = Choir::select('choirs.id', 'choirs.nama', 'choirs.logo')
                ->whereHas('events.concert', function ($query) {
                    $query->where('status', 'published');
                })
                ->where('nama', 'like', "%$keyword%")
                ->get();
        }

        return view('eticketing.search-results', compact('keyword', 'tab', 'events', 'choirs'));
    }

    public function notification()
    {
        $notifications = auth()->user()->notifications->where('data.tipe', 'eticket')->sortByDesc('created_at');

        return view('eticketing.notifications', compact('notifications'));
    }

    public function readAndRedirect($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);

        $notification->markAsRead();

        return redirect()->route('eticket.feedback', $notification->data['purchase_id']);
    }
}
