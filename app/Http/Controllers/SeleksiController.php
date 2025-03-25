<?php

namespace App\Http\Controllers;

use App\Models\Seleksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeleksiController extends Controller
{
    public function index()
    {
        $seleksiLalu = Seleksi::where('choirs_id', Auth::user()->members->first()->id)
            ->whereRaw("TIMESTAMP(tanggal_selesai, jam_selesai) < ?", [now()])
            ->get();
        $seleksiDepan = Seleksi::where('choirs_id', Auth::user()->members->first()->id)
            ->whereRaw("TIMESTAMP(tanggal_selesai, jam_selesai) > ?", [now()])
            ->get();

        return view('member.seleksi.index', compact('seleksiDepan', 'seleksiLalu'));
    }

    public function create()
    {
        return view('member.seleksi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'lokasi' => 'required|string|max:255',
        ]);
        $data = $request->except('_token');
        $data['choirs_id'] = Auth::user()->members->first()->id;
        $seleksi = Seleksi::create($data);

        return redirect()->route('seleksi.index')
            ->with('success', 'Seleksi baru berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        dd($id);
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
    public function update(Request $request, string $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $seleksi = Seleksi::find($id);
        $seleksi->delete();

        return redirect()->route('seleksi.index')
            ->with('success', 'Seleksi berhasil dihapus.');
    }
}
