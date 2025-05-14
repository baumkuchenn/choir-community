<?php

namespace App\Http\Controllers;

use App\Models\Concert;
use Illuminate\Http\Request;

class ConcertController extends Controller
{
    public function update(Request $request, string $id)
    {
        $request->validate([
            'gambar' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'deskripsi' => 'required|string|max:1000',
            'seating_plan' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'syarat_ketentuan' => 'required|string|max:1000',
            'banks_id' => 'required',
            'no_rekening' => 'required|string|max:45',
            'pemilik_rekening' => 'required|string|max:150',
            'berita_transfer' => 'required|string|max:255',
            'ebooklet' => 'required',
            'link_ebooklet' => 'nullable|string|max:255',
            'donasi' => 'required',
            'kupon' => 'required',
        ]);

        $concert = Concert::where('events_id', $id)->firstOrFail();
        $ticket = $concert->ticketTypes->where('visibility', 'public');
        if ($ticket->isEmpty()) {
            return redirect()->back()->with('error', 'Belum ada jenis tiket yang dijual');
        }
        $previousStatus = $concert->status;

        if ($request->hasFile('seating_plan')) {
            $image = $request->file('seating_plan');
            $filename = 'eventId_' . $id . '.jpg';
            $path = $image->storeAs('seating_plans', $filename, 'public');

            $concert->seating_plan = $path;
        } elseif ($request->filled('existing_seating_plan')) {
            $concert->seating_plan = $request->existing_seating_plan;
        }

        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $filename = 'eventId_' . $id . '.jpg';
            $path = $image->storeAs('events', $filename, 'public');

            $concert->gambar = $path;
        } elseif ($request->filled('existing_gambar')) {
            $concert->gambar = $request->existing_gambar;
        }

        $concert->update($request->except(['seating_plan', 'gambar']));

        if ($concert->status === 'draft') {
            $concert->update(['status' => 'published']);
        }

        $message = ($previousStatus === 'draft' && $concert->status === 'published')
            ? 'Konser berhasil diupload ke menu e-ticketing.'
            : 'Data konser berhasil diperbarui.';

        return redirect()->route('events.show', $id)->with('success', $message);
    }
}
