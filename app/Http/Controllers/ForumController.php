<?php

namespace App\Http\Controllers;

use App\Models\Concert;
use App\Models\Forum;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ForumController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $concerts = collect();
        $postConcert = collect();
        $choir = null;

        $followForums = Forum::with('members')
            ->whereHas('members', function ($query) {
                $query->where('users_id', Auth::id());
            })
            ->limit(5)
            ->get();
        $topForums = Forum::withCount('members')
            ->where('visibility', 'public')
            ->orderByDesc('members_count')
            ->limit(5)
            ->get();

        $forums = Forum::with([
            'topics.posts' => function ($query) {
                $query->withCount('replies')
                    ->with(['creator', 'postAttachments', 'userReaction', 'forum']);
            }
        ])
            ->where(function ($query) {
                $query->where('visibility', 'public');
                if (auth()->check()) {
                    $query->orWhere(function ($subQuery) {
                        $subQuery->where('visibility', 'private')
                            ->whereHas('members', function ($memberQuery) {
                                $memberQuery->where('users_id', auth()->id());
                            });
                    });
                }
            })
            ->get();
        $posts = collect();

        if ($forums) {
            $posts = $forums->flatMap(function ($forum) {
                return $forum->topics->flatMap(function ($topic) use ($forum) {
                    return $topic->posts->map(function ($post) use ($topic, $forum) {
                        $post->topic = $topic;
                        $post->forum = $forum;
                        return $post;
                    });
                });
            })->sortByDesc('created_at')->values();

            if ($user) {
                if ($user->can('akses-admin')) {
                    $choir = Auth::user()->members->first()->choir;
                    $user->name = $choir->nama;
                    $concerts = Concert::with('event.choirs')
                        ->whereHas('event.choirs', function ($query) use ($choir) {
                            $query->where('choirs.id', $choir->id);
                        })
                        ->latest()
                        ->get();
                }
                $postConcert = Post::with('postConcerts.choir')
                    ->where('tipe', 'thread')
                    ->when($choir, function ($query) use ($choir) {
                        // Choir admin: show threads created by their choir
                        $query->whereHas('postConcerts', function ($sub) use ($choir) {
                            $sub->where('choirs_id', $choir->id);
                        });
                    }, function ($query) use ($user) {
                        // Ticket buyer: show threads for concerts they bought
                        $query->whereHas('postConcerts.concert.purchases', function ($sub) use ($user) {
                            $sub->where('users_id', $user->id);
                        });
                    })
                    ->latest()
                    ->get();
            }

            $posts = $posts->merge($postConcert)->sortByDesc('created_at')->values();
        }

        return view('forum.index', compact('user', 'concerts', 'followForums', 'topForums', 'posts'));
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

        $forum->members()->create([
            'users_id' => Auth::id(),
            'jabatan' => 'admin',
        ]);

        return redirect()->route('forum.show', $slug)
            ->with('success', 'Forum berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $forum = Forum::with([
            'topics.posts' => function ($query) {
                $query->withCount('replies')
                    ->with(['creator', 'postAttachments', 'userReaction']);
            }
        ])
            ->where('slug', $slug)
            ->first();
        $posts = $forum->topics->flatMap(function ($topic) {
            return $topic->posts->map(function ($post) use ($topic) {
                $post->topic = $topic;
                return $post;
            });
        })->sortByDesc('created_at')->values();

        $user = Auth::user();
        $followForums = Forum::with('members')
            ->whereHas('members', function ($query) {
                $query->where('users_id', Auth::id());
            })
            ->limit(5)
            ->get();
        $topForums = Forum::withCount('members')
            ->where('visibility', 'public')
            ->orderByDesc('members_count')
            ->limit(5)
            ->get();
        $isMember = false;
        $jabatan = null;
        if ($user) {
            $isMember = $forum->members()->where('users_id', $user->id)->exists();
            $jabatan = $forum->getUserJabatan($user);
        }

        if ($forum->visibility == 'private' && $isMember == false) {
            return abort(404);
        }

        return view('forum.show', compact('followForums', 'topForums', 'forum', 'posts', 'isMember', 'jabatan'));
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
    public function update(Request $request, string $slug)
    {
        $request->validate([
            'nama' => 'required|string|max:45',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_banner' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'visibility' => 'required',
            'deskripsi' => 'required|string|max:250',
        ]);

        $forum = Forum::where('slug', $slug)->first();
        $forum->update($request->except(['foto_profil', 'foto_banner']));

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

        return redirect()->back()->with('success', 'Forum berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function masuk(string $slug)
    {
        $forum = Forum::where('slug', $slug)
            ->firstOrFail();
        $forum->members()->create([
            'users_id' => Auth::id(),
            'jabatan' => 'anggota',
        ]);

        return redirect()->route('forum.show', $slug)
            ->with('success', 'Berhasil bergabung ke forum.');
    }

    public function keluar(string $slug)
    {
        $forum = Forum::where('slug', $slug)
            ->firstOrFail();
        $member = $forum->members()
            ->where('users_id', Auth::id())
            ->first();

        if ($member) {
            $member->delete();
        }

        return redirect()->route('forum.show', $slug)
            ->with('success', 'Berhasil keluar dari forum.');
    }

    public function pengaturan(string $slug)
    {
        $forum = Forum::where('slug', $slug)
            ->firstOrFail();
        $members = $forum->members;

        $user = Auth::user();
        $followForums = Forum::with('members')
            ->whereHas('members', function ($query) {
                $query->where('users_id', Auth::id());
            })
            ->limit(5)
            ->get();
        $topForums = Forum::withCount('members')
            ->where('visibility', 'public')
            ->orderByDesc('members_count')
            ->limit(5)
            ->get();
        $jabatan = null;
        if ($user) {
            $jabatan = $forum->getUserJabatan($user);
        }
        return view('forum.setting', compact('forum', 'members', 'followForums', 'topForums', 'jabatan'));
    }

    public function notification()
    {
        $notifications = auth()->user()->notifications->where('data.tipe', 'forum');
        return view('forum.notifications', compact('notifications'));
    }

    public function readAndRedirect($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);

        $notification->markAsRead();

        return redirect()->route('posts.show', $notification->data['post_id']);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('search_input');
        $tab = $request->input('tab');

        $posts = collect();
        $forums = collect();

        if ($tab === 'posts') {
            $posts = Post::with(['creator', 'forum'])
                ->where(function ($query) use ($keyword) {
                    $query->where('isi', 'like', "%$keyword%")
                        ->orWhereHas('forum', function ($forumQuery) use ($keyword) {
                            $forumQuery->where('nama', 'like', "%$keyword%")
                                ->where('visibility', 'public');
                        });
                })
                ->where('parent_id')
                ->where('tipe', 'post')
                ->latest()
                ->get();
        } elseif ($tab === 'forums') {
            $forums = Forum::where('nama', 'like', "%$keyword%")
                ->where('visibility', 'public')
                ->latest()
                ->get();
        }

        $followForums = Forum::with('members')
            ->whereHas('members', function ($query) {
                $query->where('users_id', Auth::id());
            })
            ->limit(5)
            ->get();
        $topForums = Forum::withCount('members')
            ->where('visibility', 'public')
            ->orderByDesc('members_count')
            ->limit(5)
            ->get();

        return view('forum.search-results', compact('followForums', 'topForums', 'keyword', 'tab', 'forums', 'posts'));
    }
}
