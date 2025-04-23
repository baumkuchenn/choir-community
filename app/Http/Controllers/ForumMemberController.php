<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use App\Models\ForumMember;
use App\Models\User;
use Illuminate\Http\Request;

class ForumMemberController extends Controller
{
    public function create()
    {
        return view('forum.modal.member.form-add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'users_id' => 'required',
            'jabatan' => 'required',
        ]);
        ForumMember::create($request->all());

        return redirect()->back()->with('success', 'Anggota forum berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $member = ForumMember::find($id);
        return view('forum.modal.member.form-edit', compact('member'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $request->validate([
            'jabatan' => 'required',
        ]);

        $member = ForumMember::findOrFail($id);
        $member->update($request->all());

        return redirect()->back()->with('success', 'Jabatan anggota forum berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $member = ForumMember::findOrFail($id);
        $member->delete();

        return redirect()->back()->with('success', 'Anggota forum berhasil dikeluarkan.');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        $users = User::where('name', 'LIKE', "%{$search}%")
            ->limit(10)
            ->get(['id', 'name']);

        return response()->json($users->map(function ($user) {
            return ['id' => $user->id, 'text' => $user->name];
        }));
    }
}
