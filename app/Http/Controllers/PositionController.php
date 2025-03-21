<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $divisi = Division::where('choirs_id', Auth::user()->members->first()->id)->get();
        return view('role.modal.position.form-create', compact('divisi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'divisions_id' => 'required',
        ]);

        Position::create($request->all());

        return redirect()->back()->with('success', 'Jabatan pengurus berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Position $position)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $jabatan = Position::findOrFail($id);
        $divisi = Division::where('choirs_id', Auth::user()->members->first()->id)->get();
        return view('role.modal.position.form-edit', compact('jabatan', 'divisi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'divisions_id' => 'required',
        ]);

        $jabatan = Position::findOrFail($id);
        $jabatan->update($request->all());

        return redirect()->back()->with('success', 'Jabatan pengurus berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jabatan = Position::findOrFail($id);
        $jabatan->delete();

        return redirect()->back()->with('success', 'Jabatan berhasil dihapus!');
    }
}
