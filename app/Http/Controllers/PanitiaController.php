<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Panitia;
use App\Models\PanitiaDivisi;
use App\Models\PanitiaJabatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PanitiaController extends Controller
{
    public function create(string $id)
    {
        $position = PanitiaDivisi::with('jabatans')
            ->where('events_id', $id)
            ->get();
        return view('event.modal.panitia.form-add', compact('position'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'users_id' => 'required',
            'jabatan_id' => 'required'
        ]);
        $existingPanitia = Panitia::where('users_id', $request->users_id)->first();

        if ($existingPanitia) {
            return back()->with('error', 'Pengguna ini sudah terdaftar sebagai panitia.');
        }

        Panitia::create($request->all());

        return redirect()->back()->with('success', 'Panitia berhasil ditambah.');
    }

    public function edit(string $id)
    {
        $panitia = Panitia::with('user')
            ->where('id', $id)
            ->first();
        $event = Event::find($panitia->events_id);
        $eventId = $event->sub_kegiatan_id === null ? $event->id : $event->sub_kegiatan_id;
        $position = PanitiaDivisi::with('jabatans')
            ->where('events_id', $eventId)
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
        $divisi = PanitiaDivisi::where('events_id', $event->id)
            ->with('jabatans')
            ->get();

        $jabatan = $divisi->flatMap->jabatans;
        return view('panitia.setting', compact('divisi', 'jabatan', 'event'));
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
