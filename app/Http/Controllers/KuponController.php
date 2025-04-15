<?php

namespace App\Http\Controllers;

use App\Models\Kupon;
use Illuminate\Http\Request;

class KuponController extends Controller
{
    public function create(string $tipe)
    {
        if ($tipe == 'kupon') {
            return view('event.modal.kupon.form-create');
        } else if ($tipe == 'referal') {
            return view('event.modal.referal.form-create');
        }
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
        $message = "";
        if ($request->tipe == 'kupon') {
            $message = "Kupon berhasil ditambahkan.";
        } elseif ($request->tipe == 'referal') {
            $message = "Kode referal berhasil ditambahkan.";
        }

        return redirect()->back()->with('success', $message);
    }

    public function edit(string $id)
    {
        $kupon = Kupon::findOrFail($id);
        if ($kupon->tipe == 'kupon') {
            return view('event.modal.kupon.form-edit', compact('kupon'));
        } else if ($kupon->tipe == 'referal') {
            $referal = $kupon;
            return view('event.modal.referal.form-edit', compact('referal'));
        }
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
        $message = "";
        if ($kupon->tipe == 'kupon') {
            $message = "Kupon berhasil diperbarui.";
        } elseif ($kupon->tipe == 'referal') {
            $message = "Kode referal berhasil diperbarui.";
        }

        return redirect()->back()->with('success', $message);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kupon = Kupon::findOrFail($id);
        $message = "";
        if ($kupon->tipe == 'kupon') {
            $message = "Kupon berhasil dihapus.";
        } elseif ($kupon->tipe == 'referal') {
            $message = "Kode referal berhasil dihapus.";
        }
        $kupon->delete();

        return redirect()->back()->with('success', $message);
    }
}
