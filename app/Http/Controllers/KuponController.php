<?php

namespace App\Http\Controllers;

use App\Models\Concert;
use App\Models\Event;
use App\Models\Kupon;
use App\Models\Member;
use App\Models\Penyanyi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KuponController extends Controller
{
    public function create(string $event, string $tipe)
    {
        $event = Event::find($event);
        if ($tipe == 'kupon') {
            return view('event.modal.kupon.form-create', compact('event'));
        } else if ($tipe == 'referal') {
            $penyanyi = Penyanyi::with('member.user')
                ->where('events_id', $event->id)
                ->get();
            return view('event.modal.referal.form-create', compact('event', 'penyanyi'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $concert = Concert::find($request->concerts_id);
        $event = $concert->event;
        $request->validate([
            'tipe' => 'required',
        ]);
        if ($request->tipe == 'kupon') {
            $request->validate([
                'waktu_expired' => [
                    'required',
                    'date',
                    'after_or_equal:now',
                    'before_or_equal:' . $event->tanggal_selesai . ' 23:59:59',
                ],
                'kode' => 'required|string|max:45',
                'potongan' => 'required|integer|min:0',
                'jumlah' => 'required|integer|min:0',
            ]);
            Kupon::create($request->all());
        } elseif ($request->tipe == 'referal') {
            $request->validate([
                'members_id' => 'required',
            ]);
            foreach ($request->members_id as $memberId) {
                $member = Member::find($memberId);
                $referralCode = $this->generateReferralCode($member, $concert);
                Kupon::create([
                    'tipe' => 'referal',
                    'kode' => $referralCode,
                    'waktu_expired' => $event->tanggal_selesai . ' ' . $event->jam_selesai,
                    'members_id' => $member->id,
                    'concerts_id' => $concert->id,
                ]);
            }
        }
        $message = "";
        if ($request->tipe == 'kupon') {
            $message = "Kupon berhasil ditambahkan.";
        } elseif ($request->tipe == 'referal') {
            $message = "Kode referal berhasil ditambahkan.";
        }

        return redirect()->back()->with('success', $message);
    }

    function generateReferralCode($member, $concert)
    {
        $random = strtoupper(Str::random(6)); // e.g. A9X7KZ
        $name = strtoupper(substr(Str::slug($member->user->name, ''), 0, 3)); // e.g. RIK
        $concertId = $concert->id;

        return "{$random}-{$name}{$concertId}";
    }

    public function edit(string $id)
    {
        $kupon = Kupon::findOrFail($id);
        if ($kupon->tipe == 'kupon') {
            $event = $kupon->concert->event;
            return view('event.modal.kupon.form-edit', compact('kupon', 'event'));
        } else if ($kupon->tipe == 'referal') {
            $referal = $kupon;
            return view('event.modal.referal.form-edit', compact('referal'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $concert = Concert::find($request->concerts_id);
        $event = $concert->event;
        $request->validate([
            'tipe' => 'required',
            'waktu_expired' => [
                'required',
                'date',
                'after_or_equal:now',
                'before_or_equal:' . $event->tanggal_selesai . ' 23:59:59',
            ],
        ]);
        if ($request->tipe == 'kupon') {
            $request->validate([
                'kode' => 'required|string|max:45',
                'potongan' => 'required|integer|min:0',
                'jumlah' => 'required|integer|min:0',
            ]);
            $kupon = Kupon::findOrFail($id);
            $kupon->update($request->all());
        } elseif ($request->tipe == 'referal') {
            // $request->validate([
            //     'kode' => 'required|string|max:45',
            //     'potongan' => 'required|integer|min:0',
            //     'jumlah' => 'required|integer|min:0',
            //     'members_id' => 'required',
            // ]);
        }
        $message = "";
        if ($request->tipe == 'kupon') {
            $message = "Kupon berhasil diperbarui.";
        } elseif ($request->tipe == 'referal') {
            $message = "Kode referal berhasil diperbarui.";
        }

        return redirect()->back()->with('success', $message);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kupon = Kupon::findOrFail($id);
        $message = "";
        if ($kupon->tipe == 'kupon') {
            $message = "Kupon berhasil dihapus.";
        } elseif ($kupon->tipe == 'referal') {
            $message = "Kode referal berhasil dihapus.";
        }
        $kupon->delete();

        return redirect()->back()->with('success', $message);
    }
}
