<?php

namespace App\Http\Controllers;

use App\Models\Kupon;
use Illuminate\Http\Request;

class KuponController extends Controller
{
    public function create()
    {
        return view('event.modal.kupon.form-create');
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
        Kupon::create($request->all());

        return redirect()->back()->with('success', 'Kupon berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $kupon = Kupon::findOrFail($id);
        return view('event.modal.ticket-type.form-edit', compact('kupon'));
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

        $kupon = Kupon::findOrFail($id);
        $kupon->update($request->all());

        return redirect()->back()->with('success', 'Kupon berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kupon = Kupon::findOrFail($id);
        $kupon->delete();

        return redirect()->back()->with('success', 'Kupon berhasil dihapus.');
    }
}
