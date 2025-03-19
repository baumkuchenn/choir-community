<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function checkIn(string $id)
    {
        $ticket = Ticket::findOrFail($id);
        if ($ticket->check_in === 'ya') {
            return response()->json(['error' => 'Ticket already checked in'], 400);
        }

        $ticket->update([
            'check_in' => 'ya',
            'waktu_check_in' => now(),
        ]);

        return response()->json([
            'message' => 'Check-in successful',
            'waktu_check_in' => $ticket->waktu_check_in->format('Y-m-d H:i:s'),
        ]);
    }
}
