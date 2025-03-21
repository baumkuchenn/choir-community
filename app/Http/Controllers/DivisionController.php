<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DivisionController extends Controller
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
        $choirId = Auth::user()->members->first()->id;
        return view('role.modal.divisi.form-create', compact('choirId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_singkat' => 'required|string|max:45',
            'nama' => 'required|string|max:255',
            'choirs_id' => 'required',
        ]);

        Division::create($request->all());

        return redirect()->back()->with('success', 'Divisi pengurus berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Division $division)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $divisi = Division::findOrFail($id);
        $choirId = Auth::user()->members->first()->id;
        return view('role.modal.divisi.form-edit', compact('divisi', 'choirId'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_singkat' => 'required|string|max:45',
            'nama' => 'required|string|max:255',
            'choirs_id' => 'required',
        ]);

        $divisi = Division::findOrFail($id);
        $divisi->update($request->all());

        return redirect()->back()->with('success', 'Divisi berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $divisi = Division::findOrFail($id);
        $divisi->delete();

        return redirect()->back()->with('success', 'Divisi berhasil dihapus!');
    }
}
