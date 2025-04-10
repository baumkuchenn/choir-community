<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\PanitiaDivisi;
use Illuminate\Http\Request;

class PanitiaDivisiController extends Controller
{
    public function create(string $id)
    {
        $eventId = $id;
        return view('panitia.modal.divisi.form-create', compact('eventId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_singkat' => 'required|string|max:45',
            'nama' => 'required|string|max:255',
            'events_id' => 'required',
        ]);

        PanitiaDivisi::create($request->all());

        return redirect()->back()->with('success', 'Divisi panitia berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $event, string $divisi)
    {
        $divisi = PanitiaDivisi::findOrFail($divisi);
        $eventModel = Event::findOrFail($event);

        // If the sub_kegiatan_id is null, use the event's ID as eventId
        $eventId = $eventModel->sub_kegiatan_id === null ? $eventModel->id : $eventModel->sub_kegiatan_id;
        return view('panitia.modal.divisi.form-edit', compact('divisi', 'eventId'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_singkat' => 'required|string|max:45',
            'nama' => 'required|string|max:255',
            'events_id' => 'required',
        ]);
        $divisi = PanitiaDivisi::findOrFail($id);
        $divisi->update($request->all());

        return redirect()->back()->with('success', 'Divisi panitia berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $divisi = PanitiaDivisi::findOrFail($id);
        $divisi->delete();

        return redirect()->back()->with('success', 'Divisi panitia berhasil dihapus!');
    }
}
