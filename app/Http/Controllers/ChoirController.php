<?php

namespace App\Http\Controllers;

use App\Models\Choir;
use App\Models\PendaftarSeleksi;
use App\Models\Seleksi;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
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
        //Algoritma rekomendasi
        function extractCity($lokasi)
        {
            return array_map('trim', explode(',', $lokasi));
        }
        $user = auth()->user();
        if ($user) {
            $userLocation = $user->kota;

            $preferredCities = collect(extractCity($userLocation));
        }

        $recomChoirs = Choir::with(['seleksis' => function ($query) {
            $query->whereRaw("TIMESTAMP(tanggal_mulai, jam_mulai) > ?", [now()])
                ->orderByRaw("TIMESTAMP(tanggal_mulai, jam_mulai) DESC")
                ->limit(1);
            $query->orderByRaw("TIMESTAMP(tanggal_mulai, jam_mulai) DESC")
                ->limit(1);
        }])
            ->whereHas('seleksis', function ($query) {
                $query->where('pendaftaran_terakhir', '>=', now()->toDateString())
                    ->where('tipe', 'member');
                $query->where('tipe', 'member');
            })
            ->get() // get all first, then prioritize manually
            ->map(function ($choir) use ($preferredCities) {
                $choir->priority_score = 0;

                foreach ($preferredCities as $city) {
                    if (stripos($choir->alamat, $city) !== false) {
                        $choir->priority_score += 1;
                        break;
                    }
                }

                return $choir;
            })
            ->sortByDesc('priority_score')
            ->values();

        // Now paginate manually after sorting
        $page = request()->get('page', 1);
        $perPage = 10;
        $paginatedChoirs = new LengthAwarePaginator(
            $recomChoirs->forPage($page, $perPage),
            $recomChoirs->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $daftar = PendaftarSeleksi::with(['user', 'seleksi.choir'])
            ->whereHas('user', function ($query) {
                $query->where('id', Auth::id());
            })
            ->paginate(10);
        return view('choir.join', compact('paginatedChoirs', 'daftar'));
    }

    public function detail(string $id)
    {
        $seleksi = Seleksi::with('choir')->findOrFail($id);
        $pendaftar = PendaftarSeleksi::where('seleksis_id', $id)
            ->where('users_id', Auth::id())
            ->exists();
        return view('choir.detail', compact('seleksi', 'pendaftar'));
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
        $request->validate([
            'nama' => 'required|string|max:45',
            'nama_singkat' => 'required|string|max:25',
            'logo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'profil' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'tipe' => 'required|string|max:25',
            'alamat' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:1000',
        ]);

        $choir = Choir::create($request->except(['logo', 'profil']));

        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $filename = $choir->id . '.jpg';
            $path = $image->storeAs('choirs/logo', $filename, 'public');
            $choir->update(['logo' => $path]);
        }

        // Handle profil upload
        if ($request->hasFile('profil')) {
            $image = $request->file('profil');
            $filename = $choir->id . '.jpg';
            $path = $image->storeAs('choirs/profil', $filename, 'public');
            $choir->update(['profil' => $path]);
        }

        $choir->members()->create([
            'users_id' => Auth::id(),
            'admin' => 'ya',
        ]);

        return redirect()->route('management.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $choir = Choir::find($id);

        return view('choir.profile', compact('choir'));
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
    public function update(Request $request, string $id)
    {
        $choir = Choir::find($id);
        if ($request->has('jenis_rekrutmen')) {
            //For updating jenis_rekrutmen
            $choir->update([
                'jenis_rekrutmen' => $request->jenis_rekrutmen,
            ]);

            return redirect()->back()->with('success', 'Metode rekrutmen berhasil diperbarui!');
        }

        //For updating profile
        $request->validate([
            'nama' => 'required|string|max:45',
            'nama_singkat' => 'required|string|max:25',
            'logo' => 'image|mimes:jpeg,png,jpg|max:2048',
            'profil' => 'image|mimes:jpeg,png,jpg|max:2048',
            'tipe' => 'required',
            'alamat' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:1000',
        ]);

        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $filename = $choir->id . '.jpg';
            $path = $image->storeAs('choirs/logo', $filename, 'public');

            $choir->logo = $path;
        } elseif ($request->filled('existing_logo')) {
            $choir->logo = $request->existing_logo;
        }

        // Handle profil upload
        if ($request->hasFile('profil')) {
            $image = $request->file('profil');
            $filename = $choir->id . '.jpg';
            $path = $image->storeAs('choirs/profil', $filename, 'public');

            $choir->profil = $path;
        } elseif ($request->filled('existing_profil')) {
            $choir->profil = $request->existing_profil;
        }

        $choir->update($request->except(['logo', 'profil']));

        return redirect()->back()->with('success', 'Profil komunitas paduan suara berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Choir $choir)
    {
        //
    }
}
