<?php

namespace App\Http\Controllers;

use App\Models\ButirPenilaian;
use App\Models\Choir;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penyanyi = Member::where('choirs_id', Auth::user()->members->first()->id)
            ->where('admin', 'tidak')
            ->get();
        $pengurus = Member::where('choirs_id', Auth::user()->members->first()->id)
            ->where('admin', 'tidak')
            ->whereNotNull('positions_id')
            ->get();
        $choir = Choir::find(Auth::user()->members->first()->id);
        return view('member.index', compact('pengurus', 'penyanyi', 'choir'));
    }

    public function setting()
    {
        $choir = Choir::find(Auth::user()->members->first()->id);
        $butirPenilaian = ButirPenilaian::where('choirs_id', $choir->id)->get();
        return view('member.setting', compact('choir', 'butirPenilaian'));
    }

    public function create()
    {
        $choir = Choir::find(Auth::user()->members->first()->id);
        if ($choir->jenis_rekrutmen == 'seleksi') {
            return redirect()->route('seleksi.index');
        } else {
            return view('member.create');
        }
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $users = User::where('name', 'LIKE', "%{$search}%")
            ->whereDoesntHave('members', function ($query) {
                $query->where('admin', 'ya');
            })
            ->limit(10)
            ->get(['id', 'name']);

        return response()->json($users->map(function ($user) {
            return ['id' => $user->id, 'text' => $user->name];
        }));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request, string $id)
    {
        $choir = Choir::find($id)
            ->update([
                'jenis_rekrutmen' => $request->jenis_rekrutmen,
            ]);

        return redirect()->back()->with('success', 'Metode rekrutmen berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
