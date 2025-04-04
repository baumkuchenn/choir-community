<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Concert;
use App\Models\Event;
use App\Models\Invoice;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Ticket;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $eventSelanjutnya = Event::join('collabs', 'events.id', '=', 'collabs.events_id')
            ->where('choirs_id', Auth::user()->members->first()->choirs_id)
            ->whereRaw("TIMESTAMP(events.tanggal_selesai, events.jam_selesai) > ?", [now()])
            ->get();

        $eventLalu = Event::join('collabs', 'events.id', '=', 'collabs.events_id')
            ->where('choirs_id', Auth::user()->members->first()->choirs_id)
            ->whereRaw("TIMESTAMP(events.tanggal_selesai, events.jam_selesai) < ?", [now()])
            ->get();

        return view('event.index', compact('eventSelanjutnya', 'eventLalu'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $events = Event::join('collabs', 'events.id', '=', 'collabs.events_id')
            ->where('choirs_id', Auth::user()->members->first()->choirs_id)
            ->get();

        return view('event.create', compact('events'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kegiatan' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'lokasi' => 'required|string|max:255',
            'peran' => 'required|string',
            'panitia_eksternal' => 'required|string',
            'metode_rekrut_panitia' => 'required|string',
            'metode_rekrut_penyanyi' => 'required|string',
        ]);
        $event = Event::create($request->all());

        $userChoirs = Auth::user()->members->pluck('id')->toArray();
        $choirIds = array_unique(array_merge($userChoirs, $request->choirs ?? []));
        $event->choirs()->attach($choirIds);

        return redirect()->route('events.index')
            ->with('success', 'Kegiatan baru berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $event = Event::with('concert')->find($id);
        $events = Event::join('collabs', 'events.id', '=', 'collabs.events_id')
            ->where('choirs_id', Auth::user()->members->first()->choirs_id)
            ->get();
        $concert = $event->concert;
        if (!$concert) {
            Concert::create([
                'events_id' => $id,
            ]);
        }

        $emptyPaginator = new LengthAwarePaginator([], 0, 5, 1, ['path' => request()->url()]);

        $purchases = $emptyPaginator;
        $ticketTypes = $emptyPaginator;
        $donations = $emptyPaginator;
        $feedbacks = $emptyPaginator;

        $purchases = $concert->purchases()
            ->with('user:id,name,no_handphone', 'invoice.tickets:id,invoices_id,check_in')
            ->whereIn('status', ['verifikasi', 'selesai'])
            ->orderByRaw("FIELD(status, 'verifikasi', 'selesai')")
            ->orderBy('waktu_pembayaran', 'desc')
            ->paginate(5);

        $ticketTypes = $concert->ticketTypes()
            ->withCount(['purchases as terjual' => function ($query) {
                $query->whereIn('status', ['verifikasi', 'selesai'])
                    ->select(DB::raw("COALESCE(SUM(jumlah), 0)"));
            }])
            ->paginate(5);
        $donations = $concert->donations()->with('user:id,name,no_handphone')->paginate(5);
        $feedbacks = $concert->feedbacks()->with('user:id,name')->paginate(5);
        $banks = Bank::all();

        return view('event.show', compact('event', 'events', 'concert', 'banks','purchases', 'ticketTypes', 'donations', 'feedbacks'));
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
        $request->validate([
            'nama' => 'required|string|max:45',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'lokasi' => 'required|string|max:255',
        ]);

        $event = Event::findOrFail($id);
        $event->update($request->all());

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

    public function checkInShow(string $id)
    {
        $purchase = Purchase::with(['invoice', 'user:id,name'])->findOrFail($id);
        $tickets = Ticket::whereHas('invoice', function ($query) use ($id) {
            $query->where('purchases_id', $id);
        })
            ->with(['ticket_type:id,nama'])
            ->paginate(5);

        return view('event.modal.ticket.form-check-in', compact('purchase', 'tickets'));
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

    public function verifikasi(string $id)
    {
        // Update the purchase status to 'SELESAI'
        $purchase = Purchase::findOrFail($id);
        $purchase->update(['status' => 'selesai']);

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

            for ($i = 0; $i < $detail->jumlah; $i++) {
                $lastTicketNumber++;
                $barcodeCode = "TKT{$concertId}{$detail->ticket_types_id}" . str_pad($lastTicketNumber, 4, '0', STR_PAD_LEFT);
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

        return redirect()->route('events.show', $eventId)
            ->with('success', 'Verifikasi berhasil! E-tiket telah dibuat.');
    }
}
