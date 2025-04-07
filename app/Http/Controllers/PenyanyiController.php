<?php

namespace App\Http\Controllers;

use App\Models\Penyanyi;
use Illuminate\Http\Request;

class PenyanyiController extends Controller
{
    public function destroy(string $id)
    {
        $penyanyi = Penyanyi::find($id);
        $penyanyi->delete();

        return redirect()->back()->with('success', 'Penyanyi berhasil dihapus dari kegiatan!');
    }
}
