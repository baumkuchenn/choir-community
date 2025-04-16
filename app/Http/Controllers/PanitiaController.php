<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Member;
use App\Models\Panitia;
use App\Models\PanitiaDivisi;
use App\Models\PanitiaJabatan;
use App\Models\PanitiaPendaftarSeleksi;
use App\Models\Seleksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PanitiaController extends Controller
{
    public function create(string $id)
    {
        $event = Event::find($id);
        $events = Event::where('sub_kegiatan_id', $event->sub_kegiatan_id)
            ->whereNotIn('jenis_kegiatan', ['latihan', 'seleksi', 'gladi'])
            ->where('id', '!=', $event->id)
            ->get();

        $eventSeleksis = Event::with('seleksi')
            ->whereHas('seleksi', function ($query) {
                $query->where('tipe', 'panitia');
            })
            ->where('sub_kegiatan_id', $event->sub_kegiatan_id)
            ->where('jenis_kegiatan', 'seleksi')
            ->get();

        foreach ($eventSeleksis as $eventSeleksi) {
            $pendaftar = PanitiaPendaftarSeleksi::with('user.members')
                ->where('seleksis_id', $eventSeleksi->seleksi->id)
                ->where('lolos', 'ya')
                ->get();
        }

        $position = PanitiaDivisi::with('jabatans')
            ->where('events_id', $id)
            ->get();

        return view('event.modal.panitia.form-add', compact('events', 'position', 'pendaftar'));
    }

    public function store(Request $request)
    {
        $event = Event::find($request->events_id);
        $choirId = Auth::user()->members->first()->choirs_id;

        if ($request->mode == 'seleksi') {
            $request->validate([
                'users_id' => 'required',
            ]);

            foreach ($request->users_id as $userId) {
                $existingPanitia = Panitia::with('user')
                    ->where('users_id', $userId)
                    ->where('events_id', $event->id)
                    ->first();
                $existingPanitiaParent = Panitia::with('user')
                    ->where('users_id', $userId)
                    ->where('events_id', $event->sub_kegiatan_id)
                    ->first();
                $pendaftar = PanitiaPendaftarSeleksi::where('users_id', $existingPanitiaParent->user->id)
                    ->first();

                if ($existingPanitia) {
                    return back()->with('error',  $existingPanitia->user->name . ' sudah terdaftar sebagai panitia.');
                }

                Panitia::create([
                    'users_id' => $userId,
                    'events_id' => $request->events_id,
                    'tipe' => $pendaftar->tipe,
                ]);
            }
        } elseif ($request->mode == 'baru') {
            $request->validate([
                'users_id' => 'required',
                'jabatan_id' => 'required'
            ]);
            $existingPanitia = Panitia::with('user')
                ->where('users_id', $request->users_id)
                ->where('events_id', $event->id)
                ->first();
            $existingPanitiaParent = Panitia::with('user')
                ->where('users_id', $request->users_id)
                ->where('events_id', $event->sub_kegiatan_id)
                ->first();
            $member = Member::where('users_id', $request->users_id)
                ->where('choirs_id', $choirId)
                ->first();

            if ($existingPanitia) {
                return back()->with('error',  $existingPanitia->user->name . ' sudah terdaftar sebagai panitia.');
            }

            if (!$existingPanitiaParent) {
                Panitia::create([
                    'users_id' => $request->users_id,
                    'events_id' => $event->sub_kegiatan_id,
                    'tipe' => $member ? 'internal' : 'eksternal',
                ]);
            }

            Panitia::create([
                'users_id' => $request->users_id,
                'events_id' => $event->id,
                'jabatan_id' => $request->jabatan_id,
                'tipe' => $member ? 'internal' : 'eksternal',
            ]);
        } elseif ($request->mode == 'event') {
            $request->validate([
                'event_lain_id' => 'required',
            ]);
            $panitia = Panitia::where('events_id', $request->event_lain_id)
                ->get();
            foreach ($panitia as $item) {
                $cekPanitia = Panitia::where('users_id', $item->users_id)
                    ->where('events_id', $request->events_id)
                    ->first();
                if (!$cekPanitia) {
                    Panitia::create([
                        'users_id' => $item->users_id,
                        'events_id' => $request->events_id,
                        'jabatan_id' => $item->jabatan_id,
                        'tipe' => $item->tipe,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Panitia berhasil ditambah.');
    }

    public function edit(string $id)
    {
        $panitia = Panitia::with('user')
            ->where('id', $id)
            ->first();

        $event = Event::find($panitia->events_id);
        $position = PanitiaDivisi::with('jabatans')
            ->where('events_id', $event->id)
            ->get();
        return view('event.modal.panitia.form-edit', compact('panitia', 'position'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jabatan_id' => 'required',
        ]);

        $panitia = Panitia::findOrFail($id);
        $panitia->update($request->all());

        return redirect()->back()->with('success', 'Panitia berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $panitia = Panitia::find($id);
        $panitia->delete();

        return redirect()->back()->with('success', 'Panitia berhasil dihapus dari kegiatan.');
    }

    public function setting(string $id)
    {
        $event = Event::find($id);
        $choirId = Auth::user()->members->first()->choirs_id;
        $events = Event::with('choirs')
            ->whereHas('choirs', function ($query) use ($choirId) {
                $query->where('choirs.id', $choirId);
            })
            ->whereNotIn('jenis_kegiatan', ['latihan', 'seleksi', 'gladi'])
            ->where('id', '!=', $event->id)
            ->get();
        $divisi = PanitiaDivisi::where('events_id', $event->id)
            ->with('jabatans')
            ->get();

        $jabatan = $divisi->flatMap->jabatans;
        return view('panitia.setting', compact('divisi', 'jabatan', 'event', 'events'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        if ($request->boolean('only_choir_members')) {
            $choirId = Auth::user()->members->first()->choirs_id;

            $users = User::where('name', 'LIKE', "%{$search}%")
                ->whereHas('members', function ($query) use ($choirId) {
                    $query->where('choirs_id', $choirId)
                        ->where('admin', '!=', 'ya');
                })
                ->limit(10)
                ->get(['id', 'name']);
        } else {
            $users = User::where('name', 'LIKE', "%{$search}%")
                ->whereDoesntHave('members', function ($query) {
                    $query->where('admin', 'ya');
                })
                ->limit(10)
                ->get(['id', 'name']);
        }

        return response()->json($users->map(function ($user) {
            return ['id' => $user->id, 'text' => $user->name];
        }));
    }
}
