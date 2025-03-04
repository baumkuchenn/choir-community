<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Milon\Barcode\DNS1D;

use function Laravel\Prompts\table;

class EticketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $konserDekat = DB::table('events')
            ->join('concerts', 'concerts.events_id', '=', 'events.id')
            ->join('collabs', 'events.id', '=', 'collabs.events_id')
            ->join('choirs', 'choirs.id', '=', 'collabs.choirs_id')
            ->select('events.*', 'choirs.nama as penyelenggara', 'concerts.id as konser_id', 'concerts.gambar')
            ->orderBy('events.tanggal_mulai', 'asc')
            ->get();
        foreach ($konserDekat as $konser) {
            $hargaMulai = DB::table('ticket_types')
                ->where('concerts_id', $konser->konser_id)
                ->min('harga');
            $konser->hargaMulai = number_format($hargaMulai, 0, ',', '.');
        }

        $recomEvents = DB::table('events')
            ->join('concerts', 'concerts.events_id', '=', 'events.id')
            ->join('collabs', 'events.id', '=', 'collabs.events_id')
            ->join('choirs', 'choirs.id', '=', 'collabs.choirs_id')
            ->select('events.*', 'choirs.nama as penyelenggara', 'concerts.id as konser_id', 'concerts.gambar')
            ->get();
        foreach ($recomEvents as $konser) {
            $hargaMulai = DB::table('ticket_types')
                ->where('concerts_id', $konser->konser_id)
                ->min('harga');
            $konser->hargaMulai = number_format($hargaMulai, 0, ',', '.');
        }

        $penyelenggara = DB::table('events')
            ->join('concerts', 'concerts.events_id', '=', 'events.id')
            ->join('collabs', 'events.id', '=', 'collabs.events_id')
            ->join('choirs', 'choirs.id', '=', 'collabs.choirs_id')
            ->select('choirs.id', 'choirs.nama', 'choirs.logo')
            ->get();

        $purchases = null;
        if (Auth::check()) {
            $user = Auth::user();
            $purchases = DB::table('purchases')
                ->join('concerts', 'purchases.concerts_id', 'concerts.id')
                ->join('events', 'concerts.events_id', 'events.id')
                ->join('purchase_details', 'purchase_details.purchases_id', 'purchases.id')
                ->where('purchases.users_id', $user->id)
                ->where('purchases.status', 'BAYAR')
                ->select('events.nama', 'concerts.gambar', 'purchases.*', DB::raw('sum(purchase_details.jumlah) as jumlah_tiket'))
                ->groupBy('purchases.id')
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
        $concert = DB::table('events')
            ->join('concerts', 'concerts.events_id', '=', 'events.id')
            ->join('collabs', 'events.id', '=', 'collabs.events_id')
            ->join('choirs', 'choirs.id', '=', 'collabs.choirs_id')
            ->select('events.nama', 'events.tanggal_mulai', 'events.jam_mulai', 'events.lokasi', 'choirs.nama as penyelenggara', 'choirs.logo', 'concerts.*')
            ->where('events.id', $id)
            ->first();

        $tickets = DB::table('ticket_types')
            ->where('concerts_id', $concert->id)
            ->get();
        $hargaMulai = $tickets->min('harga');

        return view('eticketing.show', compact('concert', 'tickets', 'hargaMulai'))
            ->with('backUrl', url()->previous());
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

        $concert = DB::table('events')
            ->join('concerts', 'concerts.events_id', '=', 'events.id')
            ->join('collabs', 'events.id', '=', 'collabs.events_id')
            ->join('choirs', 'choirs.id', '=', 'collabs.choirs_id')
            ->select('events.nama', 'events.tanggal_mulai', 'events.jam_mulai', 'events.lokasi', 'choirs.nama as penyelenggara', 'choirs.logo', 'concerts.*')
            ->where('events.id', $id)
            ->first();

        return view('eticketing.order', compact('concert', 'tiketDipilih'));
    }

    public function purchase(Request $request, string $id)
    {
        $user = Auth::user();

        if ($request->input('purchase-menu') === 'purchase') {
            $tiketDipilih = $request->input('tickets', []);
            $tiketDipilih = array_map(fn($t) => json_decode($t, true), $tiketDipilih);
            $totalHarga = array_sum(array_map(fn($t) => $t['jumlah'] * $t['harga'], $tiketDipilih));

            $purchasesId = DB::table('purchases')->insertGetId([
                'status' => 'BAYAR',
                'total_tagihan' => $totalHarga,
                'users_id' => $user->id,
                'concerts_id' => $id,
            ]);

            foreach ($tiketDipilih as $tiket) {
                DB::table('purchase_details')->insert([
                    'purchases_id' => $purchasesId,
                    'ticket_types_id' => $tiket['id'],
                    'jumlah' => $tiket['jumlah'],
                ]);
            }

            $purchases = DB::table('purchases')->where('id', $purchasesId)->first();
        } else {
            $purchases = DB::table('purchases')->where('id', $id)->first();
            $tiketDipilih = DB::table('ticket_types')
                ->join('purchase_details', 'purchase_details.ticket_types_id', 'ticket_types.id')
                ->where('purchase_details.purchases_id', $id)
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => (string) $item->id,  // Convert to string if needed
                        'nama' => (string) $item->nama,
                        'harga' => (string) $item->harga,
                        'jumlah' => (int) $item->jumlah, // Ensure jumlah is integer
                    ];
                })
                ->toArray();
        }
        $expiredAt = Carbon::parse($purchases->waktu_pembelian)->addHours(24);
        $isExpired = now()->greaterThan($expiredAt);
        if ($isExpired && $purchases->status === 'BAYAR') {
            DB::table('purchases')->where('id', $purchases->id)->update(['status' => 'BATAL']);
        }

        $concert = DB::table('events')
            ->join('concerts', 'concerts.events_id', '=', 'events.id')
            ->join('banks', 'concerts.banks_id', '=', 'banks.id')
            ->select('events.nama', 'concerts.*', 'banks.nama_singkatan', 'banks.logo')
            ->where('concerts.id', $id)
            ->first();

        return view('eticketing.purchase', compact('tiketDipilih', 'concert', 'purchases'));
    }

    public function payment(Request $request, string $id)
    {
        if (request()->input('payment-menu') === 'payment') {
            $request->validate([
                'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $image = $request->file('bukti_pembayaran');
            $filename = time() . '_purchaseId_' . $id . '.jpg';
            $path = $image->storeAs('bukti_pembayaran', $filename, 'public');

            DB::table('purchases')
                ->where('id', $id)
                ->update([
                    'status' => 'VERIFIKASI',
                    'gambar_pembayaran' => $path,
                    'waktu_pembayaran' => now()->setTimezone('Asia/Jakarta'),
                ]);
        } else {
            $purchases = DB::table('purchases')
                ->leftJoin('invoices', 'invoices.purchases_id', 'purchases.id')
                ->where('purchases.id', $id)
                ->select('purchases.*', 'invoices.kode')
                ->first();
        }
        $tiketDibeli = DB::table('ticket_types')
            ->join('purchase_details', 'purchase_details.ticket_types_id', 'ticket_types.id')
            ->where('purchase_details.purchases_id', $id)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => (string) $item->id,  // Convert to string if needed
                    'nama' => (string) $item->nama,
                    'harga' => (string) $item->harga,
                    'jumlah' => (int) $item->jumlah, // Ensure jumlah is integer
                ];
            })
            ->toArray();

        return view('eticketing.payment-proof', compact('tiketDibeli', 'purchases'));
    }

    public function myticket()
    {
        $user = Auth::user();
        $konserBerlangsung = DB::table('purchases')
            ->join('concerts', 'purchases.concerts_id', 'concerts.id')
            ->join('events', 'concerts.events_id', 'events.id')
            ->join('purchase_details', 'purchase_details.purchases_id', 'purchases.id')
            ->join('collabs', 'events.id', '=', 'collabs.events_id')
            ->join('choirs', 'choirs.id', '=', 'collabs.choirs_id')
            ->where('purchases.users_id', $user->id)
            ->whereRaw("TIMESTAMP(events.tanggal_selesai, events.jam_selesai) > ?", [now()->setTimezone('Asia/Jakarta')])
            ->whereNotIn('purchases.status', ['BATAL', 'BAYAR'])
            ->select('events.nama', 'events.tanggal_selesai', 'concerts.gambar', 'purchases.*', DB::raw('sum(purchase_details.jumlah) as jumlah_tiket'))
            ->groupBy('purchases.id')
            ->get();

        foreach ($konserBerlangsung as $konser) {
            $konser->check_in = 'TIDAK';
            $tickets = DB::table('tickets')
                ->join('invoices', 'tickets.invoices_id', 'invoices.id')
                ->join('ticket_types', 'tickets.ticket_types_id', 'ticket_types.id')
                ->where('invoices.purchases_id', $konser->id)
                ->get();
            foreach ($tickets as $ticket) {
                if ($ticket->check_in == 'YA') {
                    $konser->check_in = 'YA';
                }
            }

            $konser->feedbacks = 'BELUM';
            $feedbacks = DB::table('feedbacks')
                ->where('concerts_id', $konser->id)
                ->where('users_id', $user->id)
                ->first();
            if ($feedbacks) {
                $konser->feedbacks = 'SUDAH';
            }
        }

        $konserLalu = DB::table('purchases')
            ->join('concerts', 'purchases.concerts_id', 'concerts.id')
            ->join('events', 'concerts.events_id', 'events.id')
            ->join('purchase_details', 'purchase_details.purchases_id', 'purchases.id')
            ->join('collabs', 'events.id', '=', 'collabs.events_id')
            ->join('choirs', 'choirs.id', '=', 'collabs.choirs_id')
            ->where('purchases.users_id', $user->id)
            ->whereRaw("TIMESTAMP(events.tanggal_selesai, events.jam_selesai) < ?", [now()->setTimezone('Asia/Jakarta')])
            ->whereNotIn('purchases.status', ['BATAL', 'BAYAR'])
            ->select('events.nama', 'events.tanggal_mulai', 'events.jam_mulai', 'events.lokasi', 'choirs.nama as penyelenggara', 'choirs.logo', 'purchases.*', DB::raw('sum(purchase_details.jumlah) as jumlah_tiket'))
            ->groupBy('purchases.id', 'events.id', 'choirs.id')
            ->get();

        foreach ($konserLalu as $konser) {
            $konser->check_in = 'TIDAK';
            $tickets = DB::table('tickets')
                ->join('invoices', 'tickets.invoices_id', 'invoices.id')
                ->join('ticket_types', 'tickets.ticket_types_id', 'ticket_types.id')
                ->where('invoices.purchases_id', $konser->id)
                ->get();
            foreach ($tickets as $ticket) {
                if ($ticket->check_in == 'YA') {
                    $konser->check_in = 'YA';
                }
            }

            $konser->feedbacks = 'BELUM';
            $feedbacks = DB::table('feedbacks')
                ->where('concerts_id', $konser->id)
                ->where('users_id', $user->id)
                ->first();
            if ($feedbacks) {
                $konser->feedbacks = 'SUDAH';
            }
        }

        return view('eticketing.myticket', compact('konserBerlangsung', 'konserLalu'));
    }

    public function invoice(string $id)
    {
        $user = Auth::user()->name;

        $events = DB::table('events')
            ->join('concerts', 'concerts.events_id', 'events.id')
            ->join('purchases', 'purchases.concerts_id', 'concerts.id')
            ->join('collabs', 'events.id', '=', 'collabs.events_id')
            ->join('choirs', 'choirs.id', '=', 'collabs.choirs_id')
            ->where('purchases.id', $id)
            ->select('events.nama', 'events.tanggal_mulai', 'events.jam_mulai', 'events.lokasi', 'concerts.gambar', 'concerts.link_ebooklet', 'concerts.syarat_ketentuan', 'choirs.nama as penyelenggara', 'choirs.logo', 'purchases.*')
            ->first();

        $invoices = DB::table('invoices')
            ->where('invoices.purchases_id', $id)
            ->first();

        $purchaseDetail = DB::table('purchase_details')
            ->join('ticket_types', 'purchase_details.ticket_types_id', 'ticket_types.id')
            ->where('purchase_details.purchases_id', $id)
            ->select('purchase_details.*', 'purchase_details.jumlah as jumlah_dibeli', 'ticket_types.*')
            ->get();

        $tickets = DB::table('tickets')
            ->join('ticket_types', 'tickets.ticket_types_id', 'ticket_types.id')
            ->where('tickets.invoices_id', $invoices->id)
            ->get();

        return view('eticketing.invoice', compact('user', 'events', 'invoices', 'tickets', 'purchaseDetail'));
    }

    public function feedback(Request $request, string $id)
    {
        if (request()->input('feedback-menu') === 'save-feedback') {
            $user = Auth::user();
            $request->validate([
                'feedback' => 'required|string|max:1000',
                'gambar-feedback' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $concertId = DB::table('purchases')
                ->where('id', $id)
                ->select('concerts_id')
                ->first()->concerts_id;

            if (request()->input('donasi') === 'YA') {
                $request->validate([
                    'nama' => 'nullable|string|max:255',
                    'nominal' => 'nullable|numeric',
                ]);

                if (request()->filled('nama') && request()->filled('jumlah')) {
                    DB::table('donations')->insert([
                        'nama' => $request->input('nama'),
                        'jumlah' => $request->input('jumlah'),
                        'concerts_id' => $concertId,
                        'users_id' => $user->id,
                    ]);
                }
            }

            $data = [
                'isi' => $request->input('feedback'),
                'concerts_id' => $concertId,
                'users_id' => $user->id,
            ];

            if ($request->hasFile('gambar-feedback')) {
                $image = $request->file('gambar-feedback');
                $filename = 'feedback_purchaseId_' . $id . '.jpg';
                $path = $image->storeAs('feedback', $filename, 'public');
                $data['gambar'] = $path;
            }
            DB::table('feedbacks')->insert($data);

            return redirect()->route('eticket.myticket')->with([
                'status' => 'success',
                'message' => 'Feedback berhasil dikirim!'
            ]);
        } else {
            $events = DB::table('events')
                ->join('concerts', 'concerts.events_id', 'events.id')
                ->join('banks', 'concerts.banks_id', 'banks_id')
                ->join('purchases', 'purchases.concerts_id', 'concerts.id')
                ->join('collabs', 'events.id', '=', 'collabs.events_id')
                ->join('choirs', 'choirs.id', '=', 'collabs.choirs_id')
                ->where('purchases.id', $id)
                ->select('events.nama', 'events.tanggal_mulai', 'events.jam_mulai', 'events.lokasi', 'concerts.gambar', 'concerts.donasi', 'concerts.no_rekening', 'concerts.pemilik_rekening', 'banks.nama_singkatan', 'banks.logo', 'choirs.nama as penyelenggara', 'choirs.logo', 'purchases.*')
                ->first();

            return view('eticketing.feedback', compact('events'));
        }
    }


    //Pemakaian nanti
    private function generateInvoiceCode($purchasesId)
    {
        // Get current date in yyyyMMdd format
        $date = now()->format('Ymd');

        $concert = DB::table('purchases')
            ->join('concerts', 'purchases.concerts_id', 'concerts.id')
            ->join('events', 'concerts.events_id', 'events.id')
            ->join('collabs', 'events.id', '=', 'collabs.events_id')
            ->where('purchases.id', $purchasesId)
            ->where('collabs.penyelenggara', 'YA')
            ->select('concerts.id as concerts_id', 'collabs.choirs_id as choirs_id')
            ->first();

        // Get abbreviation of choir name (first 3 letters as uppercase)
        $choirAbbr = strtoupper(DB::table('choirs')
            ->where('id', $concert->choirs_id)
            ->first()->nama_singkat);

        // Get the number of concerts for this choir
        $concertCount = DB::table('concerts')
            ->join('events', 'concerts.events_id', 'events.id')
            ->join('collabs', 'events.id', '=', 'collabs.events_id')
            ->where('choirs_id', $concert->choirs_id)
            ->where('collabs.penyelenggara', 'YA')
            ->count(); // Increment for the new concert

        $buyerCount = DB::table('purchases')
            ->where('concerts_id', $concert->concerts_id)
            ->where('status', 'SELESAI')
            ->count(); // Increment for the new concert

        // Format concert count with leading zeros (e.g., 001, 002)
        $concertNumber = str_pad($concertCount, 3, '0', STR_PAD_LEFT);

        // Format buyer ID with leading zeros (e.g., 001, 002)
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
        DB::table('purchases')
            ->where('id', $id)
            ->update([
                'status' => 'Selesai',
            ]);

        $invoiceCode = $this->generateInvoiceCode($id);
        $invoicesId = DB::table('invoices')->insertGetId([
            'kode' => $invoiceCode,
            'purchases_id' => $id,
        ]);

        $purchaseDetail = DB::table('purchase_details')
            ->where('purchases_id', $id)
            ->get();

        $concertId = DB::table('purchases')
            ->where('id', $id)
            ->first()->concerts_id;

        foreach ($purchaseDetail as $detail) {
            $lastTicketNumber = DB::table('tickets')
                ->where('ticket_types_id', $detail->ticket_types_id)
                ->max('number') ?? 0;

            for ($i = 0; $i < $detail->jumlah; $i++) {
                $lastTicketNumber++;
                $barcodeCode = "TKT{$concertId}{$detail->ticket_types_id}" . str_pad($lastTicketNumber, 4, '0', STR_PAD_LEFT);
                $barcodeImage = $this->generateBarcodeImage($barcodeCode);

                DB::table('tickets')->insert([
                    'number' => $lastTicketNumber,
                    'barcode_code' => $barcodeCode,
                    'barcode_image' => $barcodeImage,
                    'invoices_id' => $invoicesId,
                    'ticket_types_id' => $detail->ticket_types_id,
                ]);
            }
        }
    }
}
