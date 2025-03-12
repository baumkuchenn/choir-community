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
            'ebooklet' => 'required',
            'link_ebooklet' => 'string|max:255',
            'donasi' => 'required',
            'kupon' => 'required',
        ]);

        $concert = Concert::where('events_id', $id)->firstOrNew(['events_id' => $id]);

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

        $concert->fill($request->except(['seating_plan', 'existing_seating_plan', 'gambar', 'existing_gambar']));
        $concert->save();

        return redirect()->route('events.show', $id)
            ->with('success', $concert->wasRecentlyCreated ? 'Konser berhasil diupload ke menu e-ticketing.' : 'Data konser berhasil diperbarui.');
    }
}
