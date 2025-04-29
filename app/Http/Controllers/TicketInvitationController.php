<?php

namespace App\Http\Controllers;

use App\Mail\TicketMail;
use App\Models\Ticket;
use App\Models\TicketInvitation;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Milon\Barcode\DNS1D;

class TicketInvitationController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(string $id)
    {
        $ticketTypes = TicketType::where('concerts_id', $id)
            ->where('visibility', 'private')
            ->get();
        return view('event.modal.ticket-invites.form-create', compact('ticketTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:45',
            'no_handphone' => 'required|string|max:15',
            'email' => 'required|string|max:255',
            'jumlah' => 'required',
        ]);

        $ticketType = TicketType::find($request->ticket_types_id);
        $typeCount = $ticketType->tickets->count();

        if ($typeCount + $request->jumlah > $ticketType->jumlah) {
            return redirect()->back()->with('error', 'Total tiket undangan yang digunakan melebihi ' . $ticketType->jumlah);
        }

        $invite = TicketInvitation::create($request->all());

        $lastTicketNumber = Ticket::where('ticket_types_id', $request->ticket_types_id)
            ->max('number') ?? 0;

        $ticketType = TicketType::find($request->ticket_types_id);
        $typeCode = strtoupper(Str::limit(preg_replace('/[^A-Za-z]/', '', $ticketType->nama), 3, ''));

        for ($i = 0; $i < $request->jumlah; $i++) {
            $lastTicketNumber++;
            $barcodeCode = "{$typeCode}{$request->concerts_id}" . str_pad($lastTicketNumber, 4, '0', STR_PAD_LEFT);
            $barcodeImage = $this->generateBarcodeImage($barcodeCode);

            Ticket::create([
                'number' => $lastTicketNumber,
                'barcode_code' => $barcodeCode,
                'barcode_image' => $barcodeImage,
                'invitations_id' => $invite->id,
                'ticket_types_id' => $request->ticket_types_id,
            ]);
        }

        $invite->load('tickets.ticket_type.concert.event.choirs');

        Mail::to($request->email)->send(new TicketMail($invite));

        return redirect()->back()->with('success', 'Tamu undangan berhasil ditambahkan.');
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
}
