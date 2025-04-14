<?php

namespace App\Http\Controllers;

use App\Models\Penyanyi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenyanyiController extends Controller
{
    public function destroy(string $id)
    {
        $penyanyi = Penyanyi::find($id);
        $penyanyi->delete();

        return redirect()->back()->with('success', 'Penyanyi berhasil dihapus dari kegiatan!');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $choirId = Auth::user()->members->first()->choirs_id;

        $users = User::with('members')
            ->where('name', 'LIKE', "%{$search}%")
            ->whereHas('members', function ($query) use ($choirId) {
                $query->where('choirs_id', $choirId)
                    ->where('admin', '!=', 'ya');
            })
            ->limit(10)
            ->get(['id', 'name']);

        return response()->json($users->map(function ($user) {
            return ['id' => $user->members->first()->id, 'text' => $user->name];
        }));
    }


    public function create(){
        $choir = Auth::user()->members->first()->choir;
        return view('event.modal.penyanyi.form-add', compact('choir'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'members_id' => 'required',
            'suara' => 'required'
        ]);
        $existingPenyanyi = Penyanyi::where('members_id', $request->members_id)->first();

        if ($existingPenyanyi) {
            return back()->with('error', 'Anggota ini sudah terdaftar sebagai penyanyi.');
        }
        Penyanyi::create($request->all());
        return redirect()->back()->with('success', 'Penyanyi berhasil ditambahkan');
    }
}
