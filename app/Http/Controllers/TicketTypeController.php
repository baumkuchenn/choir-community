<?php

namespace App\Http\Controllers;

use App\Models\TicketType;
use Illuminate\Http\Request;

class TicketTypeController extends Controller
{
    public function create()
    {
        return view('event.modal.ticket-type.form-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'jumlah' => 'required|integer',
        ]);
        TicketType::create($request->all());

        return redirect()->back()->with('success', 'Jenis tiket berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $ticketType = TicketType::findOrFail($id);
        return view('event.modal.ticket-type.form-edit', compact('ticketType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'jumlah' => 'required|integer',
        ]);

        $ticketType = TicketType::findOrFail($id);
        $ticketType->update($request->all());
        
        return redirect()->back()->with('success', 'Jenis tiket berhasil diperbarui!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ticketType = TicketType::findOrFail($id);
        $ticketType->delete();
        
        return redirect()->back()->with('success', 'Jenis tiket berhasil dihapus!');
    }
}
