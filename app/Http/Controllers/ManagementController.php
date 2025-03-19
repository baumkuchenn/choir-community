<?php

namespace App\Http\Controllers;

use App\Models\Event;
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
        $choir = $user->members->first();
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
        $events = Event::select(
            'nama as title',
            'tanggal_mulai as start',
            'tanggal_selesai as end',
            'jam_mulai',
            'jam_selesai',
            'lokasi'
        )
            ->join('collabs', 'events.id', '=', 'collabs.events_id')
            ->where('choirs_id', Auth::user()->members->first()->id)
            ->get();

        return response()->json($events);
    }
}
