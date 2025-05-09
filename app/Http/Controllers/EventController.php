<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Models\Bank;
use App\Models\Choir;
use App\Models\Concert;
use App\Models\Event;
use App\Models\Invoice;
use App\Models\Latihan;
use App\Models\Member;
use App\Models\Panitia;
use App\Models\PanitiaPendaftarSeleksi;
use App\Models\PendaftarSeleksi;
use App\Models\Penyanyi;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Seleksi;
use App\Models\Ticket;
use App\Models\TicketInvitation;
use App\Models\TicketType;
use App\Notifications\DaftarEventNotification;
use App\Notifications\EventNotification;
use App\Notifications\EventUpdatedNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Milon\Barcode\DNS1D;

use function Pest\Laravel\get;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->can('akses-eticket-panitia') || $user->can('akses-event-panitia')) {
            $eventIds = $user->panitias->pluck('events_id');
            $eventSelanjutnya = Event::join('collabs', 'events.id', '=', 'collabs.events_id')
                ->whereIn('id', $eventIds)
                ->whereRaw("events.tanggal_selesai >= CURDATE()")
                ->orderBy('tanggal_mulai', 'desc')
                ->paginate(5);
            $eventLalu = Event::join('collabs', 'events.id', '=', 'collabs.events_id')
                ->whereIn('id', $eventIds)
                ->whereRaw("events.tanggal_selesai < CURDATE()")
                ->orderBy('tanggal_mulai', 'desc')
                ->paginate(5);
        }
        if ($user->can('akses-eticket') || $user->can('akses-event')) {
            $eventSelanjutnya = Event::join('collabs', 'events.id', '=', 'collabs.events_id')
                ->where('choirs_id', Auth::user()->members->first()->choirs_id)
                ->where('penyelenggara', 'ya')
                ->whereRaw("events.tanggal_selesai >= CURDATE()")
                ->orderBy('tanggal_mulai', 'desc')
                ->paginate(5);

            $eventLalu = Event::join('collabs', 'events.id', '=', 'collabs.events_id')
                ->where('choirs_id', Auth::user()->members->first()->choirs_id)
                ->where('penyelenggara', 'ya')
                ->whereRaw("events.tanggal_selesai < CURDATE()")
                ->orderBy('tanggal_mulai', 'desc')
                ->paginate(5);
        }

        return view('event.index', compact('eventSelanjutnya', 'eventLalu'));
    }

    public function searchEventSelanjutnya(Request $request)
    {
        $searchQuery = $request->input('search');

        $eventSelanjutnya = Event::join('collabs', 'events.id', '=', 'collabs.events_id')
            ->where('choirs_id', Auth::user()->members->first()->choirs_id)
            ->whereRaw("events.tanggal_selesai >= CURDATE()")
            ->where(function ($query) use ($searchQuery) {
                $query->where('nama', 'LIKE', "%{$searchQuery}%");
            })
            ->orderBy('tanggal_mulai', 'desc')
            ->paginate(5);

        return view('event.partials.event_selanjutnya_list', compact('eventSelanjutnya'));
    }

    public function searchEventLalu(Request $request)
    {
        $searchQuery = $request->input('search');

        $eventLalu = Event::join('collabs', 'events.id', '=', 'collabs.events_id')
            ->where('choirs_id', Auth::user()->members->first()->choirs_id)
            ->whereRaw("events.tanggal_selesai < CURDATE()")
            ->where(function ($query) use ($searchQuery) {
                $query->where('nama', 'LIKE', "%{$searchQuery}%");
            })
            ->orderBy('tanggal_mulai', 'desc')
            ->paginate(5);

        return view('event.partials.event_lalu_list', compact('eventLalu'));
    }

    public function searchChoir(Request $request)
    {
        if ($request->has('choirs_id')) {
            $choir = Choir::find($request->id);
            return response()->json([
                'id' => $choir->id,
                'text' => $choir->nama,
            ]);
        }
        $search = $request->input('search');
        $choirs = Choir::where('nama', 'LIKE', "%{$search}%")
            ->limit(10)
            ->get(['id', 'nama']);

        return response()->json($choirs->map(function ($choir) {
            return ['id' => $choir->id, 'text' => $choir->nama];
        }));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $events = Event::join('collabs', 'events.id', '=', 'collabs.events_id')
            ->where('choirs_id', Auth::user()->members->first()->choirs_id)
            ->where('parent_kegiatan', 'ya')
            ->get();

        return view('event.create', compact('events'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $message = "";
        $userChoirs = Auth::user()->members->pluck('id')->toArray();

        if ($request->parent_kegiatan == 'ya') {
            $request->validate([
                'nama' => 'required|string|max:255',
                'jenis_kegiatan' => 'required',
            ]);

            $event = Event::create($request->all());
            $message = "Kegiatan utama baru berhasil dibuat.";
        } else {
            $rules = [
                'nama' => 'required|string|max:255',
                'parent_kegiatan' => 'required',
                'jenis_kegiatan' => 'required|string',
                'parent_id' => 'required',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date',
            ];
            if ($request->jenis_kegiatan != 'latihan') {
                $rules = [
                    'jam_mulai' => 'required',
                    'jam_selesai' => 'required',
                    'lokasi' => 'required|string|max:255',
                ];

                if ($request->jenis_kegiatan == 'seleksi') {
                    $rules['tipe_seleksi'] = 'required';
                } elseif ($request->jenis_kegiatan === 'konser') {
                    $rules['kegiatan_kolaborasi'] = 'required';
                    $rules['peran'] = 'required';

                    if ($request->kegiatan_kolaborasi === 'ya') {
                        $rules['choirs_id'] = 'required';
                    }
                }
            }

            $request->validate($rules);
            $request->merge([
                'visibility' => $request->has('parent_display') ? 'inherited' : 'public',
            ]);

            $event = Event::create($request->all());
            $message = "Kegiatan baru berhasil dibuat.";
            if ($request->jenis_kegiatan == 'seleksi') {
                $dataSeleksi = $request->all();
                $dataSeleksi['tipe'] = $request->tipe_seleksi;
                $dataSeleksi['choirs_id'] = Auth::user()->members->first()->choirs_id;
                $dataSeleksi['events_id'] = $event->id;
                $dataSeleksi['pendaftaran_terakhir'] = Carbon::parse($request->tanggal_mulai)->subDay()->toDateString();

                Seleksi::create($dataSeleksi);
            }

            if ($request->has('all_notification')) {
                $members = Member::with('user')
                    ->where('choirs_id', $userChoirs[0])
                    ->where('admin', 'tidak')
                    ->get()
                    ->pluck('user')
                    ->filter()
                    ->unique('id');
                if ($request->jenis_kegiatan == 'seleksi') {
                    Notification::send($members, new DaftarEventNotification($event));
                } else {
                    Notification::send($members, new EventNotification($event));
                }
            } elseif ($request->has('parent_notification')) {
                $mainEventId = $event->parent_id ?? $event->id;

                $penyanyi = Penyanyi::with('member.user')
                    ->whereHas('member', function ($query) use ($userChoirs) {
                        $query->where('choirs_id', $userChoirs[0])
                            ->where('admin', 'tidak');
                    })
                    ->where('events_id', $mainEventId)
                    ->get()
                    ->pluck('member.user')
                    ->filter()
                    ->unique('id');

                $panitia = Panitia::with('user')
                    ->where('events_id', $mainEventId)
                    ->get()
                    ->pluck('user')
                    ->filter()
                    ->unique('id');

                $allRecipients = $penyanyi->merge($panitia)->unique('id');

                if ($request->jenis_kegiatan == 'seleksi') {
                    Notification::send($allRecipients, new DaftarEventNotification($event));
                    // } elseif ($request->jenis_kegiatan == 'rapat') {
                    //     Notification::send($panitia, new EventNotification($event));
                } else {
                    Notification::send($penyanyi, new EventNotification($event));
                }
            }
        }

        $choirId = Auth::user()->members->first()->choirs_id;
        $choirIds = array_unique(array_merge([$choirId], $request->choirs_id ?? []));
        $attachData = [];

        foreach ($choirIds as $id) {
            $attachData[$id] = [
                'penyelenggara' => $id == $choirId ? 'ya' : 'tidak'
            ];
        }

        $event->choirs()->attach($attachData);

        return redirect()->route('events.index')
            ->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $event = Event::with('concert')->find($id);
        $user = Auth::user();
        $choir = null;
        if ($user->members->isNotEmpty()) {
            $choir = $user->members->first()->choir;
        } else {
            $choir = $event->choirs->first();
        }
        $events = Event::join('collabs', 'events.id', '=', 'collabs.events_id')
            ->where('choirs_id', $choir->id)
            ->where('parent_kegiatan', 'ya')
            ->get();

        $concert = null;
        $penyanyi = collect();
        $panitia = collect();
        $banks = collect();
        $purchases = collect();
        $invitations = collect();
        $ticketTypes = collect();
        $donations = collect();
        $kupon = collect();
        $referal = collect();
        $feedbacks = collect();

        $seleksi = null;
        $pendaftar = collect();
        $hasil = collect();

        $latihan = collect();

        if ($event->jenis_kegiatan == 'konser') {
            $concert = $event->concert;
            if (!$concert) {
                $concert = Concert::create([
                    'events_id' => $id,
                    'status' => 'draft',
                ]);
            }

            $penyanyi = Penyanyi::where('events_id', $event->id)
                ->get();
            $panitia = Panitia::with('jabatan')
                ->where('events_id', $event->id)
                ->get();

            $purchases = $concert->purchases()
                ->with('user:id,name,no_handphone', 'invoice.tickets:id,invoices_id,check_in')
                ->whereIn('status', ['verifikasi', 'selesai', 'batal'])
                ->orderByRaw("FIELD(status, 'verifikasi', 'selesai', 'batal')")
                ->orderBy('waktu_pembayaran', 'desc')
                ->get();

            $ticketTypes = $concert->ticketTypes()
                ->withCount(['purchases as terjual' => function ($query) {
                    $query->whereIn('status', ['verifikasi', 'selesai'])
                        ->select(DB::raw("COALESCE(SUM(jumlah), 0)"));
                }])
                ->get();

            $invitations = TicketInvitation::whereHas('tickets.ticket_type', function ($query) use ($concert) {
                $query->where('concerts_id', $concert->id);
            })->get();
            $donations = $concert->donations()->with('user:id,name,no_handphone')->get();
            $kupon = $concert->kupons()->where('tipe', 'kupon')->get();
            $referal = $concert->kupons()->where('tipe', 'referal')->get();
            $feedbacks = $concert->feedbacks()->with('user:id,name')->paginate(10);
            $banks = Bank::all();
        } elseif ($event->jenis_kegiatan == 'seleksi') {
            $seleksi = Seleksi::where('events_id', $event->id)->first();
            if ($seleksi->tipe == 'event') {
                $pendaftar = PendaftarSeleksi::with('user')
                    ->where('seleksis_id', $seleksi->id)->get();
                $hasil = PendaftarSeleksi::with('user', 'nilais')
                    ->where('seleksis_id', $seleksi->id)
                    ->whereHas('nilais')
                    ->get();
            } elseif ($seleksi->tipe == 'panitia') {
                $pendaftar = PanitiaPendaftarSeleksi::with('user')
                    ->where('seleksis_id', $seleksi->id)
                    ->get();
                $hasil = PanitiaPendaftarSeleksi::with('user')
                    ->where('seleksis_id', $seleksi->id)
                    ->whereNotNull('hasil_wawancara')
                    ->get();
            }
        } elseif ($event->jenis_kegiatan == 'latihan') {
            $latihan = Latihan::where('events_id', $event->id)->get();
        } elseif ($event->jenis_kegiatan == 'event') {
            $penyanyi = Penyanyi::where('events_id', $event->id)
                ->get();
            $panitia = Panitia::with('jabatan')
                ->where('events_id', $event->id)
                ->get();
        }

        return view('event.show', compact('event', 'events', 'concert', 'choir', 'penyanyi', 'panitia', 'purchases', 'invitations', 'ticketTypes', 'donations', 'kupon', 'referal', 'feedbacks', 'banks', 'seleksi', 'pendaftar', 'hasil', 'latihan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $event = Event::findOrFail($id);
        $rules = [
            'nama' => 'required|string|max:255',
            'parent_id' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'lokasi' => 'required|string|max:255',
        ];

        if ($event->jenis_kegiatan === 'konser') {
            // $rules['kegiatan_kolaborasi'] = 'required';

            if ($request->kegiatan_kolaborasi === 'ya') {
                $rules['choirs_id'] = 'required';
            }
        }

        $request->validate($rules);
        $request->merge([
            'visibility' => $request->has('parent_display') ? 'inherited' : 'public',
        ]);

        $event->update($request->all());

        $userChoirs = Auth::user()->members->pluck('id')->toArray();

        $mainEventId = $event->parent_id ?? $event->id;

        $penyanyi = Penyanyi::with('member.user')
            ->whereHas('member', function ($query) use ($userChoirs) {
                $query->where('choirs_id', $userChoirs[0])
                    ->where('admin', 'tidak');
            })
            ->where('events_id', $mainEventId)
            ->get()
            ->pluck('member.user')
            ->filter()
            ->unique('id');
        $panitia = Panitia::with('user')
            ->where('events_id', $mainEventId)
            ->get()
            ->pluck('user')
            ->filter()
            ->unique('id');

        $allRecipients = $penyanyi->merge($panitia)->unique('id');

        if ($request->has('notification')) {
            if ($request->jenis_kegiatan == 'latihan' || $request->jenis_kegiatan == 'gladi') {
                Notification::send($penyanyi, new EventUpdatedNotification($event));
                // } elseif ($request->jenis_kegiatan == 'rapat') {
                //     Notification::send($panitia, new EventUpdatedNotification($event));
            } else {
                Notification::send($allRecipients, new EventUpdatedNotification($event));
            }
        }

        return redirect()->route('events.show', $id)
            ->with('success', 'Perubahan detail kegiatan berhasil.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = Event::find($id);
        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }

    public function checkInShow(string $name, string $id)
    {
        $purchase = null;
        $invitation = null;
        $tickets = collect();

        if ($name == 'invite') {
            $invitation = TicketInvitation::with('tickets')->findOrFail($id);
            $tickets = Ticket::whereHas('ticketInvitation', function ($query) use ($id) {
                $query->where('id', $id);
            })
                ->with(['ticket_type:id,nama'])
                ->paginate(5);
        } elseif ($name == 'purchase') {
            $purchase = Purchase::with(['invoice', 'user:id,name'])->findOrFail($id);
            $tickets = Ticket::whereHas('invoice', function ($query) use ($id) {
                $query->where('purchases_id', $id);
            })
                ->with(['ticket_type:id,nama'])
                ->paginate(5);
        }

        return view('event.modal.ticket.form-check-in', compact('purchase', 'invitation', 'tickets'));
    }

    public function payment(Request $request, string $id)
    {
        $purchases = Purchase::with('invoice:id,purchases_id,kode')
            ->where('id', $id)
            ->first();

        $tiketDibeli = TicketType::whereHas('purchases', function ($query) use ($id) {
            $query->where('purchases.id', $id);
        })
            ->with(['purchases' => function ($query) use ($id) {
                $query->where('purchases.id', $id);
            }])
            ->get()
            ->map(function ($item) {
                return [
                    'id' => (string) $item->id,
                    'nama' => (string) $item->nama,
                    'harga' => (string) $item->harga,
                    'jumlah' => (int) $item->purchases->first()->pivot->jumlah,
                ];
            })
            ->toArray();

        return view('event.payment', compact('tiketDibeli', 'purchases'));
    }

    private function generateInvoiceCode($purchasesId)
    {
        // Get current date in yyyyMMdd format
        $date = now()->format('Ymd');

        // Retrieve the related concert and event using Eloquent relationships
        $purchase = Purchase::with('concert.event.choirs')->findOrFail($purchasesId);

        $concert = $purchase->concert;
        $event = $concert->event;

        // Find the choir where 'penyelenggara' = 'YA'
        $choir = $event->choirs->where('pivot.penyelenggara', 'ya')->first();

        // Get abbreviation of choir name (first 3 letters as uppercase)
        $choirAbbr = strtoupper(substr($choir->nama_singkat, 0, 3));

        // Get the number of concerts for this choir
        $concertCount = Concert::whereHas('event.choirs', function ($query) use ($choir) {
            $query->where('choirs.id', $choir->id)
                ->where('collabs.penyelenggara', 'ya'); // Filtering in many-to-many
        })->count();

        // Get the number of completed purchases for this concert
        $buyerCount = Purchase::where('concerts_id', $concert->id)
            ->where('status', 'selesai')
            ->count();

        // Format numbers with leading zeros
        $concertNumber = str_pad($concertCount, 3, '0', STR_PAD_LEFT);
        $buyerNumber = str_pad($buyerCount, 3, '0', STR_PAD_LEFT);

        // Combine into final invoice code
        return "INV/{$date}/{$choirAbbr}/CON{$concertNumber}/{$buyerNumber}";
    }


    private function generateBarcodeImage($barcodeCode)
    {
        $dns = new DNS1D();
        $barcodeData = $dns->getBarcodePNG($barcodeCode, 'C128', 2, 50);

        // Convert base64 to binary image data
        $barcodeImage = base64_decode($barcodeData);

        // Define file path
        $imagePath = "barcodes/{$barcodeCode}.png";

        // Save the image to storage
        Storage::disk('public')->put($imagePath, $barcodeImage);

        return $imagePath; // Return the image path
    }

    public function verifikasi(Request $request, string $id)
    {
        // Update the purchase status to 'SELESAI'
        $purchase = Purchase::with(['concert.event.choirs', 'ticketTypes', 'invoice.tickets.ticket_type', 'user'])
            ->findOrFail($id);

        $purchase->update(['status' => $request->status_verifikasi == 'terima' ? 'selesai' : 'batal']);

        if ($request->status_verifikasi == 'batal') {
            return redirect()->route('events.show', $purchase->concert->event->id)
                ->with('error', 'Pembayaran tiket telah dibatalkan.');
        }

        // Generate and save the invoice
        $invoiceCode = $this->generateInvoiceCode($id);
        $invoice = Invoice::create([
            'kode' => $invoiceCode,
            'purchases_id' => $id,
        ]);

        // Retrieve purchase details
        $purchaseDetails = PurchaseDetail::where('purchases_id', $id)->get();

        // Get concert ID from the purchase
        $eventId = $purchase->concert->event->id;
        $concertId = $purchase->concerts_id;

        // Process tickets
        foreach ($purchaseDetails as $detail) {
            $lastTicketNumber = Ticket::where('ticket_types_id', $detail->ticket_types_id)
                ->max('number') ?? 0;
            $ticketType = TicketType::find($detail->ticket_types_id);
            $typeCode = strtoupper(Str::limit(preg_replace('/[^A-Za-z]/', '', $ticketType->nama), 3, ''));

            for ($i = 0; $i < $detail->jumlah; $i++) {
                $lastTicketNumber++;
                $barcodeCode = "{$typeCode}{$concertId}" . str_pad($lastTicketNumber, 4, '0', STR_PAD_LEFT);
                $barcodeImage = $this->generateBarcodeImage($barcodeCode);

                Ticket::create([
                    'number' => $lastTicketNumber,
                    'barcode_code' => $barcodeCode,
                    'barcode_image' => $barcodeImage,
                    'invoices_id' => $invoice->id,
                    'ticket_types_id' => $detail->ticket_types_id,
                ]);
            }
        }

        $purchase->load([
            'concert.event.choirs',
            'ticketTypes',
            'invoice.tickets.ticket_type',
            'user'
        ]);

        Mail::to($purchase->user->email)->send(new InvoiceMail($purchase));


        return redirect()->route('events.show', $eventId)
            ->with('success', 'Verifikasi berhasil! E-tiket telah dibuat.');
    }
}
