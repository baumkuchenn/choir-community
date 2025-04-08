<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Latihan;
use App\Models\Penyanyi;
use App\Notifications\EventNotification;
use App\Notifications\LatihanNotification;
use App\Notifications\LatihanUpdatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class LatihanController extends Controller
{
    public function create()
    {
        return view('event.modal.latihan.form-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'lokasi' => 'required|string|max:255',
        ]);

        Latihan::create($request->all());

        if ($request->kegiatan_berulang == 'ya') {
            $request->validate([
                'interval' => 'required|integer|min:1',
                'frekuensi' => 'required|in:minggu,bulan',
                'hari' => 'nullable|array',
                'tipe_selesai' => 'required|in:tidak,tanggal,jumlah',
                'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal',
                'jumlah' => 'nullable|integer|min:1',
            ]);
        }

        if ($request->has('parent_notification')) {
            $event = Event::find($request->events_id);
            $userChoirs = Auth::user()->members->pluck('id')->toArray();
            $mainEventId = $event->sub_kegiatan_id ?? $event->id;

            $penyanyiUsers = Penyanyi::with('member.user')
                ->whereHas('member', function ($query) use ($userChoirs) {
                    $query->where('choirs_id', $userChoirs[0])
                        ->where('admin', 'tidak');
                })
                ->where('events_id', $mainEventId)
                ->get()
                ->pluck('member.user')
                ->filter()
                ->unique('id');

            Notification::send($penyanyiUsers, new LatihanNotification($latihan, $event));
        }

        return redirect()->back()->with('success', 'Jadwal latihan berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $latihan = Latihan::findOrFail($id);
        return view('event.modal.latihan.form-edit', compact('latihan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'lokasi' => 'required|string|max:255',
        ]);

        $latihan = Latihan::findOrFail($id);
        $latihan->update($request->all());

        if ($request->has('parent_notification')) {
            $event = Event::find($request->events_id);
            $userChoirs = Auth::user()->members->pluck('id')->toArray();
            $mainEventId = $event->sub_kegiatan_id ?? $event->id;

            $penyanyiUsers = Penyanyi::with('member.user')
                ->whereHas('member', function ($query) use ($userChoirs) {
                    $query->where('choirs_id', $userChoirs[0])
                        ->where('admin', 'tidak');
                })
                ->where('events_id', $mainEventId)
                ->get()
                ->pluck('member.user')
                ->filter()
                ->unique('id');

            Notification::send($penyanyiUsers, new LatihanUpdatedNotification($latihan, $event));
        }

        return redirect()->back()->with('success', 'Jadwal latihan berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $latihan = Latihan::findOrFail($id);
        $latihan->delete();

        return redirect()->back()->with('success', 'Jadwal latihan berhasil dihapus!');
    }
}
