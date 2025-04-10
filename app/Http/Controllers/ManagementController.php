<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Latihan;
use App\Models\PanitiaPendaftarSeleksi;
use App\Models\PendaftarSeleksi;
use App\Models\PendaftarSeleksiPanitia;
use App\Models\Seleksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $choir = $user->members->first()->choir;
        $notifications = $user->notifications()->latest()->take(5)->get();

        return view('management.index', compact('choir', 'notifications'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function calendar()
    {
        return view('management.calendar');
    }

    public function calendarShow()
    {
        $events = Event::select(
            'nama as title',
            'tanggal_mulai as start',
            'tanggal_selesai as end',
            'jam_mulai',
            'jam_selesai',
            'lokasi'
        )
            ->join('collabs', 'events.id', '=', 'collabs.events_id')
            ->where('choirs_id', Auth::user()->members->first()->choirs_id)
            ->where('jenis_kegiatan', '!=', 'latihan')
            ->get()
            ->map(function ($event) {
                return [
                    'title' => $event->title,
                    'start' => $event->start,
                    'end'   => Carbon::parse($event->start)->eq(Carbon::parse($event->end))
                        ? $event->end // one-day event
                        : Carbon::parse($event->end)->addDay()->toDateString(), // add +1
                    'jam_mulai' => $event->jam_mulai,
                    'jam_selesai' => $event->jam_selesai,
                    'lokasi' => $event->lokasi,
                    'allDay' => true,
                ];
            });

        $latihans = Latihan::select(
            'events.nama as title',
            'latihans.tanggal as start',
            'latihans.tanggal as end',
            'latihans.jam_mulai',
            'latihans.jam_selesai',
            'latihans.lokasi'
        )
            ->join('events', 'latihans.events_id', '=', 'events.id')
            ->join('collabs', 'events.id', '=', 'collabs.events_id')
            ->where('collabs.choirs_id', Auth::user()->members->first()->choirs_id)
            ->get();
        // Merge and return as one collection
        $combined = $events->concat($latihans);

        return response()->json($combined);
    }

    public function notification()
    {
        $user = Auth::user();
        $choir = $user->members->first()->choir;
        $notifications = $user->notifications()->latest()->take(5)->get();

        return view('management.notifications', compact('choir', 'notifications'));
    }

    public function daftar(string $id)
    {
        $event = Event::find($id);
        if ($event->jenis_kegiatan == 'seleksi') {
            $seleksi = Seleksi::where('events_id', $event->id)->first();

            if ($seleksi->tipe == 'event') {
                PendaftarSeleksi::create([
                    'users_id' => Auth::id(),
                    'seleksis_id' => $seleksi->id,
                ]);
            } elseif ($seleksi->tipe == 'panitia') {
                PanitiaPendaftarSeleksi::create([
                    'users_id' => Auth::id(),
                    'seleksis_id' => $seleksi->id,
                    'tipe' => ($event->choirs_id == (Auth::user()->members->first()->choirs_id ?? null)) ? 'internal' : 'eksternal',
                ]);
            }
        }

        return redirect()->route('management.index')
            ->with('success', 'Berhasil mendaftar kegiatan.');
    }
}
