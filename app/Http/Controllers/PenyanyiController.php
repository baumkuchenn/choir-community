<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Member;
use App\Models\PendaftarSeleksi;
use App\Models\Penyanyi;
use App\Models\Seleksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenyanyiController extends Controller
{
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


    public function create(string $event)
    {
        $choir = Auth::user()->members->first()->choir;
        $event = Event::find($event);
        $events = Event::where('sub_kegiatan_id', $event->sub_kegiatan_id)
            ->whereNotIn('jenis_kegiatan', ['latihan', 'seleksi', 'gladi'])
            ->where('id', '!=', $event->id)
            ->get();
        $seleksis = Event::with('seleksi')
            ->whereHas('seleksi', function ($query) {
                $query->where('tipe', 'event');
            })
            ->where('sub_kegiatan_id', $event->sub_kegiatan_id)
            ->where('jenis_kegiatan', 'seleksi')
            ->get();

        foreach ($seleksis as $seleksi) {
            $pendaftar = PendaftarSeleksi::with('user.members')
                ->where('seleksis_id', $seleksi->seleksi->id)
                ->where('lolos', 'ya')
                ->get();
        }

        return view('event.modal.penyanyi.form-add', compact('choir', 'pendaftar', 'events'));
    }

    public function store(Request $request)
    {
        $event = Event::find($request->events_id);

        if ($request->mode == 'seleksi') {
            $request->validate([
                'members_id' => 'required',
            ]);
            foreach ($request->members_id as $memberId) {
                $existingPenyanyi = Penyanyi::with('member.user')
                    ->where('members_id', $memberId)
                    ->where('events_id', $event->id)
                    ->first();
                $existingPenyanyiParent = Penyanyi::with('member.user')
                    ->where('members_id', $memberId)
                    ->where('events_id', $event->sub_kegiatan_id)
                    ->first();
                $pendaftar = PendaftarSeleksi::where('users_id', $existingPenyanyiParent->member->user->id)
                    ->first();
                if ($existingPenyanyi) {
                    return back()->with('error',  $existingPenyanyi->member->user->name . ' sudah terdaftar sebagai penyanyi.');
                }

                Penyanyi::create([
                    'members_id' => $memberId,
                    'events_id' => $request->events_id,
                    'suara' => $pendaftar->kategori_suara,
                ]);
            }
        } elseif ($request->mode == 'baru') {
            $request->validate([
                'members_id' => 'required',
                'suara' => 'required',
            ]);
            $existingPenyanyi = Penyanyi::with('member.user')
                ->where('members_id', $request->members_id)
                ->where('events_id', $event->id)
                ->first();
            $existingPenyanyiParent = Penyanyi::with('member.user')
                ->where('members_id', $request->members_id)
                ->where('events_id', $event->sub_kegiatan_id)
                ->first();

            if ($existingPenyanyi) {
                return back()->with('error',  $existingPenyanyi->member->user->name . ' sudah terdaftar sebagai penyanyi.');
            }

            if (!$existingPenyanyiParent) {
                Penyanyi::create([
                    'members_id' => $request->members_id,
                    'events_id' => $event->sub_kegiatan_id,
                    'suara' => $request->suara,
                ]);
            }

            Penyanyi::create([
                'members_id' => $request->members_id,
                'events_id' => $event->id,
                'suara' => $request->suara,
            ]);
        } elseif ($request->mode == 'event') {
            $request->validate([
                'event_lain_id' => 'required',
            ]);
            $penyanyi = Penyanyi::where('events_id', $request->event_lain_id)
                ->get();
            foreach ($penyanyi as $item) {
                $cekPenyanyi = Penyanyi::where('members_id', $item->members_id)
                    ->where('events_id', $request->events_id)
                    ->first();
                if (!$cekPenyanyi) {
                    Penyanyi::create([
                        'members_id' => $item->members_id,
                        'events_id' => $request->events_id,
                        'suara' => $item->suara,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Penyanyi berhasil ditambahkan');
    }

    public function destroy(string $id)
    {
        $penyanyi = Penyanyi::find($id);
        $penyanyi->delete();

        return redirect()->back()->with('success', 'Penyanyi berhasil dihapus dari kegiatan!');
    }
}
