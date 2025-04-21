<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ForumController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->can('akses-admin')) {
            $user = Auth::user()->members->first()->choir;
            $user->name = $user->nama;
        }
        $followForums = Forum::with('members')
            ->where('creator_id', $user->id)
            ->limit(5)
            ->get();
        $topForums = Forum::withCount('members')
            ->where('visibility', 'public')
            ->orderByDesc('members_count')
            ->limit(5)
            ->get();

        return view('forum.index', compact('user', 'followForums', 'topForums'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('forum.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:45',
            'foto_profil' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'foto_banner' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'visibility' => 'required',
            'deskripsi' => 'required|string|max:250',
        ]);
        $slug = Str::slug($request->nama);

        $originalSlug = $slug;
        $counter = 1;
        while (Forum::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $data = $request->all();
        $data['slug'] = $slug;
        $data['creator_id'] = Auth::id();

        $forum = Forum::create($data);

        if ($request->hasFile('foto_profil')) {
            $image = $request->file('foto_profil');
            $filename = $forum->id . '.jpg';
            $path = $image->storeAs('forums/profil', $filename, 'public');
            $forum->update(['foto_profil' => $path]);
        }

        if ($request->hasFile('foto_banner')) {
            $image = $request->file('foto_banner');
            $filename = $forum->id . '.jpg';
            $path = $image->storeAs('forums/banner', $filename, 'public');
            $forum->update(['foto_banner' => $path]);
        }

        return redirect()->route('forum.show', $slug)
            ->with('success', 'Forum berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $forum = Forum::where('slug', $slug)
            ->firstOrFail();

        $user = Auth::user();
        $followForums = Forum::with('members')
            ->where('creator_id', $user->id)
            ->limit(5)
            ->get();
        $topForums = Forum::withCount('members')
            ->where('visibility', 'public')
            ->orderByDesc('members_count')
            ->limit(5)
            ->get();

        return view('forum.show', compact('forum', 'followForums', 'topForums'));
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
}
