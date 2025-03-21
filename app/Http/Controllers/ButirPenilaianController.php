<?php

namespace App\Http\Controllers;

use App\Models\ButirPenilaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ButirPenilaianController extends Controller
{
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $choirId = Auth::user()->members->first()->id;
        return view('member.modal.butir-penilaian.form-create', compact('choirId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'bobot_nilai' => 'required|max:100',
            'choirs_id' => 'required',
        ]);

        $totalNilai = ButirPenilaian::where('choirs_id', $request->choirs_id)->sum('bobot_nilai') + $request->bobot_nilai;

        if ($totalNilai > 100) {
            return redirect()->back()->with('error', 'Total bobot nilai tidak boleh lebih dari 100%!');
        }

        ButirPenilaian::create($request->all());

        return redirect()->back()->with('success', 'Butir penilaian seleksi berhasil ditambahkan!');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $butirPenilaian = ButirPenilaian::findOrFail($id);
        $choirId = Auth::user()->members->first()->id;
        return view('member.modal.butir-penilaian.form-edit', compact('butirPenilaian', 'choirId'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'bobot_nilai' => 'required|max:100',
            'choirs_id' => 'required',
        ]);

        $totalNilai = ButirPenilaian::where('choirs_id', $request->choirs_id)->where('id', '!=', $id)->sum('bobot_nilai') + $request->bobot_nilai;

        if ($totalNilai > 100) {
            return redirect()->back()->with('error', 'Total bobot nilai tidak boleh lebih dari 100%!');
        }

        $butirPenilaian = ButirPenilaian::findOrFail($id);
        $butirPenilaian->update($request->all());

        return redirect()->back()->with('success', 'Butir penilaian seleksi berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $butirPenilaian = ButirPenilaian::findOrFail($id);
        $butirPenilaian->delete();

        return redirect()->back()->with('success', 'Butir penilaian seleksi berhasil dihapus!');
    }
}
