<?php

namespace App\Http\Controllers;

use App\Models\Choir;
use App\Models\Concert;
use App\Models\Donation;
use App\Models\Event;
use App\Models\Feedback;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\TicketType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        $recomEvents = Event::with(['concert', 'choirs'])
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
            ->get()
            ->map(function ($event) {
                $event->choir = $event->choirs->first();
                unset($event->choirs);
                return $event;
            });

        foreach ($recomEvents as $konser) {
            $hargaMulai = TicketType::where('concerts_id', $konser->concert->id ?? null)
                ->where('visibility', 'public')
                ->min('harga');
            $konser->hargaMulai = number_format($hargaMulai, 0, ',', '.');
        }

        $penyelenggara = Choir::select('choirs.id', 'choirs.nama', 'choirs.logo')
            ->whereHas('events.concert', function ($query) {
                $query->where('status', 'published');
            })
            ->get();

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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
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

            $purchase = Purchase::create([
                'status' => 'bayar',
                'total_tagihan' => $totalHarga,
                'users_id' => $user->id,
                'concerts_id' => $id,
            ]);

            foreach ($tiketDipilih as $tiket) {
                PurchaseDetail::create([
                    'purchases_id' => $purchase->id,
                    'ticket_types_id' => $tiket['id'],
                    'jumlah' => $tiket['jumlah'],
                ]);
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

            Purchase::where('id', $id)->update([
                'status' => 'verifikasi',
                'gambar_pembayaran' => $path,
                'waktu_pembayaran' => now(),
            ]);
        }

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
                $purchase->feedbacks = $purchase->concert->feedback ? 'sudah' : 'belum';

                return $purchase;
            });

        $purchaseLalu = Purchase::with([
            'concert.event.choirs',
            'ticketTypes',
            'invoice.tickets',
            'concert.feedbacks'
        ])
            ->where('users_id', $user->id)
            ->whereHas('concert.event', function ($query) {
                $query->whereRaw("TIMESTAMP(tanggal_selesai, jam_selesai) < ?", [now()]);
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
                $purchase->feedbacks = $purchase->concert->feedback ? 'sudah' : 'belum';

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
        if ($request->input('feedback-menu') === 'save-feedback') {
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
                    'nama' => 'nullable|string|max:255',
                    'nominal' => 'nullable|numeric',
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
        } else {
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
    }
}
