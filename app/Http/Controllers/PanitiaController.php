<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Panitia;
use App\Models\PanitiaDivisi;
use App\Models\PanitiaJabatan;
use Illuminate\Http\Request;

class PanitiaController extends Controller
{
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

        return redirect()->back()->with('success', 'Jenis tiket berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $panitia = Panitia::find($id);
        $panitia->delete();

        return redirect()->back()->with('success', 'Panitia berhasil dihapus dari kegiatan!');
    }

    public function setting(string $id)
    {
        $event = Event::find($id);
        $eventId = $event->sub_kegiatan_id === null ? $id : $event->sub_kegiatan_id;
        $divisi = PanitiaDivisi::where('events_id', $eventId)
            ->with('jabatans')
            ->get();

        $jabatan = $divisi->flatMap->jabatans;
        $event = Event::find($id);
        return view('panitia.setting', compact('divisi', 'jabatan', 'event'));
    }
}
