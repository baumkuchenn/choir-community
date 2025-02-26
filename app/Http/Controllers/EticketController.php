<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\table;

class EticketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $konserDekat = DB::table('events')
            ->join('concerts', 'concerts.events_id', '=', 'events.id')
            ->join('collabs', 'events.id', '=', 'collabs.events_id')
            ->join('choirs', 'choirs.id', '=', 'collabs.choirs_id')
            ->select('events.*', 'choirs.nama as penyelenggara', 'concerts.id as konser_id', 'concerts.gambar')
            ->orderBy('events.tanggal_mulai', 'asc')
            ->get();
        foreach ($konserDekat as $konser) {
            $hargaMulai = DB::table('ticket_types')
                ->where('concerts_id', $konser->konser_id)
                ->min('harga');
            $konser->hargaMulai = number_format($hargaMulai, 0, ',', '.');
        }

        $recomEvents = DB::table('events')
            ->join('concerts', 'concerts.events_id', '=', 'events.id')
            ->join('collabs', 'events.id', '=', 'collabs.events_id')
            ->join('choirs', 'choirs.id', '=', 'collabs.choirs_id')
            ->select('events.*', 'choirs.nama as penyelenggara', 'concerts.id as konser_id', 'concerts.gambar')
            ->get();
        foreach ($recomEvents as $konser) {
            $hargaMulai = DB::table('ticket_types')
                ->where('concerts_id', $konser->konser_id)
                ->min('harga');
            $konser->hargaMulai = number_format($hargaMulai, 0, ',', '.');
        }

        $penyelenggara = DB::table('events')
            ->join('concerts', 'concerts.events_id', '=', 'events.id')
            ->join('collabs', 'events.id', '=', 'collabs.events_id')
            ->join('choirs', 'choirs.id', '=', 'collabs.choirs_id')
            ->select('choirs.id', 'choirs.nama', 'choirs.logo')
            ->get();

        return view('eticketing.index', compact('konserDekat', 'recomEvents', 'penyelenggara'));
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
        $concert = DB::table('events')
            ->join('concerts', 'concerts.events_id', '=', 'events.id')
            ->join('collabs', 'events.id', '=', 'collabs.events_id')
            ->join('choirs', 'choirs.id', '=', 'collabs.choirs_id')
            ->select('events.nama', 'events.tanggal_mulai', 'events.jam_mulai', 'events.lokasi', 'choirs.nama as penyelenggara', 'choirs.logo', 'concerts.*')
            ->where('events.id', $id)
            ->first();

        $tickets = DB::table('ticket_types')
            ->where('concerts_id', $concert->id)
            ->get();
        $hargaMulai = $tickets->min('harga');

        return view('eticketing.show', compact('concert', 'tickets', 'hargaMulai'))
            ->with('backUrl', url()->previous());;
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

    public function purchase(string $id)
    {
        $user = Auth::user()->makeHidden('password');
        $concert = DB::table('events')
            ->join('concerts', 'concerts.events_id', '=', 'events.id')
            ->join('collabs', 'events.id', '=', 'collabs.events_id')
            ->join('choirs', 'choirs.id', '=', 'collabs.choirs_id')
            ->select('events.nama', 'events.tanggal_mulai', 'events.jam_mulai', 'events.lokasi', 'choirs.nama as penyelenggara', 'choirs.logo', 'concerts.*')
            ->where('events.id', $id)
            ->first();

        return view('eticketing.purchase', compact('user', 'concert'))
            ->with('backUrl', url()->previous());;
    }
}
