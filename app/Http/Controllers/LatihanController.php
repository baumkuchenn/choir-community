<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Latihan;
use App\Models\Penyanyi;
use App\Notifications\EventNotification;
use App\Notifications\LatihanNotification;
use App\Notifications\LatihanUpdatedNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class LatihanController extends Controller
{
    public function create()
    {
        $hari = [
            'monday' => 'Senin',
            'tuesday' => 'Selasa',
            'wednesday' => 'Rabu',
            'thursday' => 'Kamis',
            'friday' => 'Jumat',
            'saturday' => 'Sabtu',
            'sunday' => 'Minggu',
        ];

        $tipeSelesai = [
            'tidak' => 'Tidak pernah',
            'tanggal' => 'Tanggal',
            'jumlah' => 'Perulangan',
        ];

        return view('event.modal.latihan.form-create', compact('hari', 'tipeSelesai'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'lokasi' => 'required|string|max:255',
        ]);

        $latihan = Latihan::create($request->all());

        if ($request->kegiatan_berulang == 'ya') {
            $request->validate([
                'interval' => 'required|integer|min:1',
                'frekuensi' => 'required|in:minggu,bulan',
                'hari' => 'nullable|array',
                'tipe_selesai' => 'required|in:tidak,tanggal,jumlah',
                'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal',
                'jumlah' => 'nullable|integer|min:1',
            ]);

            $startDate = Carbon::parse($request->tanggal);
            $jamMulai = $request->jam_mulai;
            $jamSelesai = $request->jam_selesai;
            $lokasi = $request->lokasi;
            $eventId = $request->events_id;

            $frekuensi = $request->frekuensi; // minggu / bulan
            $interval = (int) $request->interval;
            $selectedDays = $request->hari ?? [];

            $created = 0;
            $maxLoop = 100; // Prevent infinite loop
            $currentDate = $startDate->copy();

            while (true) {
                // Break condition
                if ($request->tipe_selesai == 'jumlah' && $created >= $request->jumlah_perulangan) break;
                if ($request->tipe_selesai == 'tanggal' && $currentDate->gt(Carbon::parse($request->tanggal_berakhir))) break;
                if ($request->tipe_selesai == 'tidak' && $created >= $maxLoop) break;

                // Loop through the days in the current "week" or "month"
                if ($frekuensi == 'minggu') {
                    foreach ($selectedDays as $day) {
                        $date = $currentDate->copy()->next($day); // e.g., next Wednesday
                        if ($date->lessThanOrEqualTo($startDate)) continue; // Skip if it's the same as start
                        if ($request->tipe_selesai == 'tanggal' && $date->gt(Carbon::parse($request->tanggal_berakhir))) break;

                        Latihan::create([
                            'events_id' => $eventId,
                            'tanggal' => $date->toDateString(),
                            'jam_mulai' => $jamMulai,
                            'jam_selesai' => $jamSelesai,
                            'lokasi' => $lokasi,
                        ]);
                        $created++;
                        if ($request->tipe_selesai == 'jumlah' && $created >= $request->jumlah_perulangan) break;
                    }
                    $currentDate->addWeeks($interval);
                } elseif ($frekuensi == 'bulan') {
                    foreach ($selectedDays as $day) {
                        $monthStart = $currentDate->copy()->startOfMonth();
                        $date = $monthStart->copy()->next($day);
                        if ($date->lessThanOrEqualTo($startDate)) continue;
                        if ($request->tipe_selesai == 'tanggal' && $date->gt(Carbon::parse($request->tanggal_berakhir))) break;

                        Latihan::create([
                            'events_id' => $eventId,
                            'tanggal' => $date->toDateString(),
                            'jam_mulai' => $jamMulai,
                            'jam_selesai' => $jamSelesai,
                            'lokasi' => $lokasi,
                        ]);
                        $created++;
                        if ($request->tipe_selesai == 'jumlah' && $created >= $request->jumlah_perulangan) break;
                    }
                    $currentDate->addMonths($interval);
                }
            }
        }

        if ($request->has('parent_notification')) {
            $event = Event::find($request->events_id);
            $userChoirs = Auth::user()->members->pluck('id')->toArray();
            $mainEventId = $event->parent_id ?? $event->id;

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
            $mainEventId = $event->parent_id ?? $event->id;

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
