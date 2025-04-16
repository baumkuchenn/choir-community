<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\PanitiaDivisi;
use App\Models\PanitiaJabatan;
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

    public function ambilKegiatanLain(string $id, Request $request)
    {
        $divisiLain = PanitiaDivisi::with('jabatans')
            ->where('events_id', $request->event_lain_id)
            ->get();
        foreach ($divisiLain as $divisi) {
            $cekDivisi = PanitiaDivisi::where('events_id', $id)
                ->where('nama', $divisi->nama)
                ->first();
            if (!$cekDivisi) {
                $divisiBaru = PanitiaDivisi::create([
                    'nama' => $divisi->nama,
                    'nama_singkat' => $divisi->nama_singkat,
                    'events_id' => $id,
                ]);
                foreach ($divisi->jabatans as $jabatan) {
                    $data = $jabatan->toArray();
                    unset($data['id']); // avoid ID duplication
                    $data['divisi_id'] = $divisiBaru->id;
                    PanitiaJabatan::create($data);
                }
            }
        }

        return redirect()->back()->with('success', 'Divisi dan jabatan panitia berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $event, string $divisi)
    {
        $divisi = PanitiaDivisi::findOrFail($divisi);
        $eventId = $event;
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
