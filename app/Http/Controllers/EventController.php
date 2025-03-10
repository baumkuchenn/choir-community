<?php

namespace App\Http\Controllers;

use App\Models\Concert;
use App\Models\Event;
use App\Models\Invoice;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Ticket;
use App\Models\TicketType;
use Illuminate\Http\Request;
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
            ->where('choirs_id', Auth::user()->choirs->first()->id)
            ->whereRaw("TIMESTAMP(events.tanggal_selesai, events.jam_selesai) > ?", [now()->setTimezone('Asia/Jakarta')])
            ->get();

        $eventLalu = Event::join('collabs', 'events.id', '=', 'collabs.events_id')
            ->where('choirs_id', Auth::user()->choirs->first()->id)
            ->whereRaw("TIMESTAMP(events.tanggal_selesai, events.jam_selesai) < ?", [now()->setTimezone('Asia/Jakarta')])
            ->get();

        return view('event.index', compact('eventSelanjutnya', 'eventLalu'));
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
        $event = Event::with('concert')->find($id);
        $events = Event::join('collabs', 'events.id', '=', 'collabs.events_id')
            ->where('choirs_id', Auth::user()->choirs->first()->id)
            ->get();
        $concert = $event->concert;
        $purchases = $concert->purchases()
            ->with('user:id,name,no_handphone', 'invoice.tickets:id,invoices_id,check_in')
            ->whereIn('status', ['VERIFIKASI', 'SELESAI'])
            ->orderByRaw("FIELD(status, 'VERIFIKASI', 'SELESAI')")
            ->orderBy('waktu_pembayaran', 'desc')
            ->paginate(5);

        $ticketTypes = $concert->ticketTypes()
            ->withCount(['purchases as terjual' => function ($query) {
                $query->whereIn('status', ['VERIFIKASI', 'SELESAI'])
                    ->select(DB::raw("COALESCE(SUM(jumlah), 0)"));
            }])
            ->paginate(5);
        $donations = $concert->donations()->with('user:id,name,no_handphone')->paginate(5);
        $feedbacks = $concert->feedbacks()->with('user:id,name')->paginate(5);


        return view('event.show', compact('event', 'events', 'concert', 'purchases', 'ticketTypes', 'donations', 'feedbacks'));
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

    public function checkIn(string $id)
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
        $choir = $event->choirs->where('pivot.penyelenggara', 'YA')->first();

        // Get abbreviation of choir name (first 3 letters as uppercase)
        $choirAbbr = strtoupper(substr($choir->nama_singkat, 0, 3));

        // Get the number of concerts for this choir
        $concertCount = Concert::whereHas('event.choirs', function ($query) use ($choir) {
            $query->where('choirs.id', $choir->id)
                ->where('collabs.penyelenggara', 'YA'); // Filtering in many-to-many
        })->count();

        // Get the number of completed purchases for this concert
        $buyerCount = Purchase::where('concerts_id', $concert->id)
            ->where('status', 'SELESAI')
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
        $purchase->update(['status' => 'SELESAI']);

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
