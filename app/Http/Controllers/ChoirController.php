<?php

namespace App\Http\Controllers;

use App\Models\Choir;
use App\Models\PendaftarSeleksi;
use App\Models\Seleksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChoirController extends Controller
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
        return view('choir.create');
    }

    public function search(Request $request)
    {
        $searchQuery = $request->input('search');

        $choir = Choir::with(['seleksis' => function ($query) {
            $query->whereRaw("TIMESTAMP(tanggal_mulai, jam_mulai) > ?", [now()])
                ->orderByRaw("TIMESTAMP(tanggal_mulai, jam_mulai) DESC")
                ->limit(1);
        }])
            ->whereHas('seleksis', function ($query) {
                $query->where('pendaftaran_terakhir', '>=', now()->toDateString());
            })
            ->where(function ($query) use ($searchQuery) {
                $query->where('nama', 'LIKE', "%{$searchQuery}%")
                    ->orWhere('kota', 'LIKE', "%{$searchQuery}%");
            })
            ->paginate(10);

        return view('choir.partials.choir_list', compact('choir'))->render();
    }

    public function join()
    {
        $choir = Choir::with(['seleksis' => function ($query) {
            $query->whereRaw("TIMESTAMP(tanggal_mulai, jam_mulai) > ?", [now()])
                ->orderByRaw("TIMESTAMP(tanggal_mulai, jam_mulai) DESC")
                ->limit(1);
        }])
            ->whereHas('seleksis', function ($query) {
                $query->where('pendaftaran_terakhir', '>=', now()->toDateString());
            })
            ->paginate(10);
        $daftar = PendaftarSeleksi::with(['user', 'seleksi.choir'])
            ->whereHas('user', function ($query) {
                $query->where('id', Auth::id());
            })
            ->get();
        return view('choir.join', compact('choir', 'daftar'));
    }

    public function detail(string $id)
    {
        $seleksi = Seleksi::with('choir')->findOrFail($id);
        return view('choir.detail', compact('seleksi'));
    }

    public function register(string $id)
    {
        PendaftarSeleksi::create([
            'users_id' => Auth::id(),
            'seleksis_id' => $id,
        ]);

        return redirect()->route('choir.join')
            ->with('success', 'Berhasil mendaftar seleksi.');
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
    public function show(Choir $choir)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Choir $choir)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Choir $choir)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Choir $choir)
    {
        //
    }
}
