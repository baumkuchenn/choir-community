<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class PersonalInfoController extends Controller
{
    public function showForm()
    {
        return view('auth.personal-info');
    }

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'no_handphone' => 'required|string|max:255|regex:/^\d+$/',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string|max:255',
            'kota' => 'required|string|max:45',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'name' => $request->name,
            'no_handphone' => $request->no_handphone,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'kota' => $request->kota,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('eticket.index')->with('success', 'Registrasi berhasil!');
    }
}
