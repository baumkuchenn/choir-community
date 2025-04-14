<?php

namespace App\Http\Controllers;

use App\Models\ButirPenilaian;
use App\Models\Event;
use App\Models\Member;
use App\Models\Panitia;
use App\Models\PanitiaPendaftarSeleksi;
use App\Models\PendaftarSeleksi;
use App\Models\Penyanyi;
use App\Models\Seleksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeleksiController extends Controller
{
    public function index()
    {
        $seleksiLalu = Seleksi::where('choirs_id', Auth::user()->members->first()->choirs_id)
            ->whereRaw("TIMESTAMP(tanggal_selesai, jam_selesai) < ?", [now()])
            ->where('tipe', 'member')
            ->get();
        $seleksiDepan = Seleksi::where('choirs_id', Auth::user()->members->first()->choirs_id)
            ->whereRaw("TIMESTAMP(tanggal_selesai, jam_selesai) > ?", [now()])
            ->where('tipe', 'member')
            ->get();

        return view('member.seleksi.index', compact('seleksiDepan', 'seleksiLalu'));
    }

    public function create()
    {
        return view('member.seleksi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'lokasi' => 'required|string|max:255',
        ]);
        $data = $request->all();
        $data['type'] = 'member';
        $data['choirs_id'] = Auth::user()->members->first()->choirs_id;
        Seleksi::create($data);

        return redirect()->route('seleksi.index')
            ->with('success', 'Seleksi baru berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $seleksi = Seleksi::find($id);
        $pendaftar = PendaftarSeleksi::with('user')->where('seleksis_id', $id)->get();
        $hasil = PendaftarSeleksi::with('user', 'nilais')->where('seleksis_id', $id)
            ->whereHas('nilais')
            ->get();
        return view('member.seleksi.show', compact('seleksi', 'pendaftar', 'hasil'));
    }

    public function tambahPendaftar(Request $request)
    {
        $existingPendaftar = PendaftarSeleksi::where('seleksis_id', $request->seleksis_id)
            ->where('users_id', $request->user_id)
            ->get();
        if ($existingPendaftar) {
            return back()->with('error', 'Anggota ini sudah terdaftar.');
        }
        PendaftarSeleksi::create($request->all());
        return redirect()->back()->with('success', 'Pendaftar berhasil ditambahkan');
    }

    public function wawancara(string $seleksiId, string $userId)
    {
        $seleksi = Seleksi::find($seleksiId);
        $butir = collect();

        if ($seleksi->tipe == 'event' || $seleksi->tipe == 'member') {
            $pendaftar = PendaftarSeleksi::with('user', 'nilais')
                ->where('seleksis_id', $seleksiId)
                ->where('users_id', $userId)
                ->first();
            $butir = ButirPenilaian::where('choirs_id', $seleksi->choirs_id)->get();
        } elseif ($seleksi->tipe == 'panitia') {
            $pendaftar = PanitiaPendaftarSeleksi::with('user')
                ->where('users_id', $userId)
                ->first();
        }

        return view('member.seleksi.detail', compact('seleksi', 'pendaftar', 'butir'));
    }

    public function checkIn(Request $request)
    {
        $seleksi = Seleksi::find($request->input('seleksis_id'));
        if ($seleksi->tipe == 'event' || $seleksi->tipe == 'member') {
            $pendaftar = PendaftarSeleksi::where('seleksis_id', $request->input('seleksis_id'))
                ->where('users_id', $request->input('users_id'))
                ->first();
        } elseif ($seleksi->tipe == 'panitia') {
            $pendaftar = PanitiaPendaftarSeleksi::where('seleksis_id', $request->input('seleksis_id'))
                ->where('users_id', $request->input('users_id'))
                ->first();
        }
        $pendaftar->update([
            'kehadiran' => $request->input('kehadiran'),
        ]);
        return redirect()->route('seleksi.wawancara', ['seleksi' => $request->input('seleksis_id'), 'user' => $request->input('users_id')])
            ->with('success', 'Status kehadiran berhasil diperbarui.');
    }

    public function lolos(Request $request)
    {
        $seleksi = Seleksi::find($request->seleksis_id);

        if ($seleksi->tipe == 'event' || $seleksi->tipe == 'member') {
            $pendaftar = PendaftarSeleksi::where('seleksis_id', $request->input('seleksis_id'))
                ->where('users_id', $request->input('users_id'))
                ->first();
        } elseif ($seleksi->tipe == 'panitia') {
            $pendaftar = PanitiaPendaftarSeleksi::where('seleksis_id', $request->input('seleksis_id'))
                ->where('users_id', $request->input('users_id'))
                ->first();
        }
        $pendaftar->update([
            'lolos' => $request->input('is_lolos'),
        ]);

        if (!is_null($seleksi->events_id)) {
            $event = Event::find($seleksi->events_id);
            if ($pendaftar->lolos == 'ya') {
                if ($seleksi->tipe == 'event') {
                    Penyanyi::create([
                        'events_id' => $event->sub_kegiatan_id,
                        'members_id' => Member::where('users_id', $request->users_id)->first()->id,
                        'suara' => $pendaftar->kategori_suara,
                    ]);
                } elseif ($seleksi->tipe == 'panitia') {
                    Panitia::create([
                        'events_id' => $event->sub_kegiatan_id,
                        'users_id' => $request->users_id,
                    ]);
                }
            }
        } else {
            if ($pendaftar->lolos == 'ya') {
                Member::create([
                    'choirs_id' => Auth::user()->members->first()->choirs_id,
                    'users_id' => $request->input('users_id'),
                    'suara' => $pendaftar->kategori_suara,
                ]);
            }
        }

        if ($seleksi->tipe == 'event' || $seleksi->tipe == 'panitia') {
            return redirect()->route('events.show', $seleksi->events_id)
                ->with('success', 'Status pendaftar berhasil diperbarui.');
        } elseif ($seleksi->tipe == 'member') {
            return redirect()->route('seleksi.show', $request->input('seleksis_id'))
                ->with('success', 'Status pendaftar berhasil diperbarui.');
        }
    }

    public function simpanPendaftar(Request $request)
    {
        $seleksi = Seleksi::find($request->input('seleksis_id'));
        $request->validate([
            'hasil_wawancara' => 'required',
        ]);

        if ($seleksi->tipe == 'event' || $seleksi->tipe == 'member') {
            $request->validate([
                'butir_penilaians' => 'required|array',
                'butir_penilaians.*.id' => 'exists:butir_penilaians,id',
                'butir_penilaians.*.nilai' => 'required|numeric',
                'lembar_penilaian' => 'required|mimes:jpg,png,jpeg,pdf|max:2048',
                'kategori_suara' => 'required',
                'range_suara' => 'required',
            ]);

            $pendaftar = PendaftarSeleksi::where('seleksis_id', $request->input('seleksis_id'))
                ->where('users_id', $request->input('users_id'))
                ->first();

            if ($request->hasFile('lembar_penilaian')) {
                $file = $request->file('lembar_penilaian');
                $extension = $file->getClientOriginalExtension();
                $fileName = 'seleksi_' . $request->input('seleksis_id') . '_user_' . $request->input('users_id') . '.' . $extension;
                $filePath = $file->storeAs('seleksis/lembar_penilaian', $fileName, 'public');
            }

            $pendaftar->update([
                'hasil_wawancara' => $request->input('hasil_wawancara'),
                'range_suara' => $request->input('range_suara'),
                'kategori_suara' => $request->input('kategori_suara'),
                'lembar_penilaian' => $filePath
            ]);

            foreach ($request->input('butir_penilaians') as $butir) {
                $pendaftar->nilais()->attach($butir['id'], [
                    'nilai' => $butir['nilai'] * ($butir['bobot_nilai'] / 100),
                ]);
            }
        } elseif ($seleksi->tipe == 'panitia') {
            $pendaftar = PanitiaPendaftarSeleksi::where('seleksis_id', $request->input('seleksis_id'))
                ->where('users_id', $request->input('users_id'))
                ->first();

            $pendaftar->update([
                'hasil_wawancara' => $request->input('hasil_wawancara'),
            ]);
        }

        if ($seleksi->tipe == 'event' || $seleksi->tipe == 'panitia') {
            return redirect()->route('events.show', $seleksi->events_id)
                ->with('success', 'Simpan data wawancara berhasil.');
        } elseif ($seleksi->tipe == 'member') {
            return redirect()->route('seleksi.show', $request->input('seleksis_id'))
                ->with('success', 'Simpan data wawancara berhasil.');
        }
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
    public function update(Request $request, string $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $seleksi = Seleksi::find($id);
        $seleksi->delete();

        return redirect()->route('seleksi.index')
            ->with('success', 'Seleksi berhasil dihapus.');
    }
}
