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
        $choir = null;
        $member = $user->members->first();
        if ($member) {
            $choir = $member->choir;
        }
        $notifications = $user->unreadNotifications->where('data.tipe', 'manajemen')->sortByDesc('created_at');
        $panitia = $user->panitias;

        return view('management.index', compact('choir', 'notifications', 'panitia'));
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
        $choirId = Auth::user()->members->first()->choirs_id;
        $events = Event::with('choirs')
            ->whereHas('choirs', function ($query) use ($choirId) {
                $query->where('choirs.id', $choirId);
            })
            ->where('jenis_kegiatan', '!=', 'latihan')
            ->where(function ($q) {
                $q->where('visibility', '!=', 'inherited')
                    ->orWhere(function ($nested) {
                        $nested->where('visibility', 'inherited')
                            ->whereHas('parent', function ($parentQuery) {
                                $parentQuery->where(function ($q2) {
                                    $q2->whereHas('panitias.user', function ($panitiaQuery) {
                                        $panitiaQuery->where('users.id', Auth::id());
                                    })->orWhereHas('penyanyis.member.user', function ($penyanyiQuery) {
                                        $penyanyiQuery->where('users.id', Auth::id());
                                    });
                                });
                            });
                    });
            })
            ->get()
            ->map(function ($event) {
                return [
                    'title' => $event->nama,
                    'start' => $event->tanggal_mulai,
                    'end'   => Carbon::parse($event->tanggal_mulai)->eq(Carbon::parse($event->tanggal_selesai))
                        ? $event->tanggal_selesai // one-day event
                        : Carbon::parse($event->tanggal_selesai)->addDay()->toDateString(), // add +1
                    'jam_mulai' => $event->jam_mulai,
                    'jam_selesai' => $event->jam_selesai,
                    'lokasi' => $event->lokasi,
                ];
            });

        $latihans = Latihan::with(['event.choirs', 'event.parent.panitias', 'event.parent.penyanyis'])
            ->whereHas('event', function ($eventQuery) use ($choirId) {
                $eventQuery->whereHas('choirs', function ($choirQuery) use ($choirId) {
                    $choirQuery->where('choirs.id', $choirId);
                })->where(function ($q) {
                    $q->where('visibility', '!=', 'inherited')
                        ->orWhere(function ($nested) {
                            $nested->where('visibility', 'inherited')
                                ->whereHas('parent', function ($parentQuery) {
                                    $parentQuery->where(function ($q2) {
                                        $q2->whereHas('panitias.user', function ($panitiaQuery) {
                                            $panitiaQuery->where('users.id', Auth::id());
                                        })->orWhereHas('penyanyis.member.user', function ($penyanyiQuery) {
                                            $penyanyiQuery->where('users.id', Auth::id());
                                        });
                                    });
                                });
                        });
                });
            })
            ->get()
            ->map(function ($latihan) {
                return [
                    'title' => $latihan->event->nama,
                    'start' => $latihan->tanggal,
                    'end' => $latihan->tanggal,
                    'jam_mulai' => $latihan->jam_mulai,
                    'jam_selesai' => $latihan->jam_selesai,
                    'lokasi' => $latihan->lokasi,
                ];
            });
        // Merge and return as one collection
        $combined = $events->concat($latihans);

        return response()->json($combined);
    }

    public function notification()
    {
        $notifications = auth()->user()->notifications->where('data.tipe', 'manajemen')->sortByDesc('created_at');

        return view('management.notifications', compact('notifications'));
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
