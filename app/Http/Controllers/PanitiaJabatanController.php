<?php

namespace App\Http\Controllers;

use App\Models\PanitiaDivisi;
use App\Models\PanitiaJabatan;
use Illuminate\Http\Request;

class PanitiaJabatanController extends Controller
{
    public function create(string $id)
    {
        $divisi = PanitiaDivisi::where('events_id', $id)->get();
        return view('panitia.modal.jabatan.form-create', compact('divisi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'divisi_id' => 'required',
        ]);

        PanitiaJabatan::create($request->all());

        return redirect()->back()->with('success', 'Jabatan panitia berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $event, string $jabatan)
    {
        $jabatan = PanitiaJabatan::findOrFail($jabatan);
        $divisi = PanitiaDivisi::where('events_id', $event)->get();
        return view('panitia.modal.jabatan.form-edit', compact('jabatan', 'divisi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'divisi_id' => 'required',
        ]);

        $jabatan = PanitiaJabatan::findOrFail($id);

        $data = $request->all();
        $data['akses_eticket'] = $request->has('akses_eticket') ? '1' : '0';
        $data['akses_event'] = $request->has('akses_event') ? '1' : '0';

        $jabatan->update($data);

        return redirect()->back()->with('success', 'Jabatan panitia berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jabatan = PanitiaJabatan::findOrFail($id);
        $jabatan->delete();

        return redirect()->back()->with('success', 'Jabatan panitia berhasil dihapus!');
    }
}
