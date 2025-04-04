<?php

namespace App\Http\Controllers;

use App\Models\Kota;
use Illuminate\Http\Request;

class KotaController extends Controller
{
    public function search(Request $request)
    {
        if ($request->has('id')) {
            $kota = Kota::find($request->id);
            return response()->json([
                'id' => $kota->id,
                'text' => $kota->nama,
            ]);
        }
        $search = $request->input('search');
        $kotas = Kota::where('nama', 'LIKE', "%{$search}%")
            ->limit(10)
            ->get(['id', 'nama']);

        return response()->json($kotas->map(function ($kota) {
            return ['id' => $kota->id, 'text' => $kota->nama];
        }));
    }
}
