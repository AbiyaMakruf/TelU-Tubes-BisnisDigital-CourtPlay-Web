<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectDetail;
use App\Models\Hwinfo;
use App\Models\Post;
use App\Helpers\CloudRunMetrics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Throwable;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * ===== Dashboard =====
     */
    public function dashboard()
    {
        try {
            // === 1. Statistik umum ===
            $stats = [
                'users'       => User::count(),
                'admins'      => User::where('role', 'admin')->count(),
                'projects'    => Project::count(),
                'completed'   => Project::where('is_mailed', true)->count(),
                'in_progress' => Project::where('is_mailed', false)->count(),
            ];

            // === 2. Top users ===
            $topUsers = User::select('id', 'first_name', 'last_name', 'username')
                ->withCount('projects')
                ->orderByDesc('projects_count')
                ->limit(5)
                ->get();

            // === 3. Tren proyek (7d–365d) ===
            $trend = [
                '7d'   => $this->buildTrend(7),
                '14d'  => $this->buildTrend(14),
                '30d'  => $this->buildTrend(30),
                '365d' => $this->buildTrend(365),
            ];

            // === 4. Cloud Run / Custom Metrics ===
            $projectId   = env('GOOGLE_CLOUD_PROJECT', 'courtplay-analytics-474615');
            $serviceName = env('CLOUD_RUN_SERVICE', 'courtplay-web');

            // Ambil semua metrics 24 jam terakhir (interval 5 menit)
            $metricsData = \App\Helpers\CloudRunMetrics::getMetrics($projectId, $serviceName);

            // Pisahkan hasil metric agar mudah diakses di Blade
            $requestMetrics  = $metricsData['metrics']['request_count'] ?? [];
            $latencyMetrics  = $metricsData['metrics']['request_latencies'] ?? [];
            $instanceMetrics = $metricsData['metrics']['container_instances'] ?? [];

            // dd($metricsData);

            Log::info('Dashboard viewed', [
                'admin_id'    => auth()->id(),
                'projectId'   => $projectId,
                'serviceName' => $serviceName,
            ]);

            // === 5. Kirim semua data ke view ===
            return view('admin.dashboard', compact(
                'stats',
                'topUsers',
                'trend',
                'requestMetrics',
                'latencyMetrics',
                'instanceMetrics'
            ));
        } catch (\Throwable $e) {
            Log::error('Dashboard load failed', ['error' => $e->getMessage()]);
            toastr()->error('Failed to load dashboard.');
            return back();
        }
    }


    private function buildTrend(int $days): array
    {
        $end   = Carbon::today();
        $start = (clone $end)->subDays($days - 1);

        $rows = Project::selectRaw("DATE(COALESCE(upload_date, created_at)) as d, COUNT(*) as c")
            ->whereBetween(DB::raw("DATE(COALESCE(upload_date, created_at))"), [$start, $end])
            ->groupBy('d')
            ->orderBy('d')
            ->pluck('c', 'd');

        $labels = [];
        $data   = [];

        foreach (CarbonPeriod::create($start, $end) as $date) {
            $key      = $date->toDateString();
            $labels[] = $date->format('d M');
            $data[]   = (int) ($rows[$key] ?? 0);
        }

        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * ===== USERS =====
     */
    public function usersIndex(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $users = User::when($q, function ($qr) use ($q) {
                $qr->where(function ($w) use ($q) {
                    $w->where('first_name', 'like', "%$q%")
                      ->orWhere('last_name', 'like', "%$q%")
                      ->orWhere('username', 'like', "%$q%")
                      ->orWhere('email', 'like', "%$q%");
                });
            })
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $roleCounts = User::select('role', DB::raw('COUNT(*) as total'))
            ->groupBy('role')
            ->pluck('total', 'role')
            ->toArray();

        return view('admin.users.index', compact('users', 'q', 'roleCounts'));
    }

    public function usersUpdateRole(Request $request, User $user)
    {
        try {
            $data = $request->validate([
                'role' => ['required', Rule::in(['free', 'pro', 'plus', 'admin'])],
            ]);

            $old = $user->role;
            $user->update(['role' => $data['role']]);

            Log::info('Admin updated user role', [
                'admin_id' => auth()->id(),
                'user_id'  => $user->id,
                'old_role' => $old,
                'new_role' => $user->role,
            ]);

            toastr()->success("Role updated from {$old} → {$user->role}");
            return back();
        } catch (Throwable $e) {
            Log::error('Update user role failed', ['error' => $e->getMessage()]);
            toastr()->error('Failed to update user role.');
            return back();
        }
    }

    public function usersDestroy(User $user)
    {
        try {
            DB::transaction(function () use ($user) {
                foreach (Project::where('user_id', $user->id)->get() as $p) {
                    Hwinfo::where('project_id', $p->id)->delete();
                    ProjectDetail::where('id', $p->project_details_id)->delete();
                    $p->delete();
                }
                $user->delete();
            });

            Log::warning('User deleted', ['admin_id' => auth()->id(), 'user_id' => $user->id]);
            toastr()->success('User and related data deleted.');
            return back();
        } catch (Throwable $e) {
            Log::error('Delete user failed', ['error' => $e->getMessage()]);
            toastr()->error('Failed to delete user.');
            return back();
        }
    }

    /**
     * ===== PROJECTS =====
     */
    public function projectsIndex(Request $request)
    {
        try {
            $q = trim((string) $request->get('q', ''));

            $projects = Project::with(['user:id,username,email'])
                ->when($q, function ($query) use ($q) {
                    $query->where('project_name', 'like', "%{$q}%")
                        ->orWhereHas('user', function ($userQuery) use ($q) {
                            $userQuery->where('username', 'like', "%{$q}%");
                        });
                })
                ->orderByDesc('created_at')
                ->paginate(15)
                ->withQueryString();

            return view('admin.projects.index', compact('projects', 'q'));
        } catch (Throwable $e) {
            Log::error('Fetch projects failed', ['error' => $e->getMessage()]);
            toastr()->error('Failed to load projects.');
            return back();
        }
    }

    public function projectsDestroy(Project $project)
    {
        try {
            DB::transaction(function () use ($project) {
                Hwinfo::where('project_id', $project->id)->delete();
                ProjectDetail::where('id', $project->project_details_id)->delete();
                $project->delete();
            });

            Log::warning('Project deleted', ['admin_id' => auth()->id(), 'project_id' => $project->id]);
            toastr()->success('Project deleted successfully.');
            return back();
        } catch (Throwable $e) {
            Log::error('Delete project failed', ['error' => $e->getMessage()]);
            toastr()->error('Failed to delete project.');
            return back();
        }
    }

    /**
     * ===== POSTS (News) =====
     */
    public function postsIndex(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $posts = Post::when($q, function ($qr) use ($q) {
                $qr->where('title', 'like', "%$q%")
                   ->orWhere('slug', 'like', "%$q%");
            })
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.posts.index', compact('posts', 'q'));
    }

    public function postsCreate()
    {
        return view('admin.posts.form', ['post' => new Post()]);
    }

    public function postsStore(Request $request)
    {
        try {
            $data = $request->validate([
                'title'        => ['required', 'string', 'max:255'],
                'slug'         => ['nullable', 'string', 'max:255', 'unique:posts,slug'],
                'excerpt'      => ['nullable', 'string', 'max:280'],
                'content'      => ['nullable', 'string'],
                'is_published' => ['nullable', 'boolean'],
                'published_at' => ['nullable', 'date'],
                'cover'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            ]);

            $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
            $data['is_published'] = (bool) ($data['is_published'] ?? false);

            $post = Post::create([
                'title'        => $data['title'],
                'slug'         => $data['slug'],
                'excerpt'      => $data['excerpt'] ?? null,
                'content'      => $data['content'] ?? null,
                'is_published' => $data['is_published'],
                'published_at' => $data['published_at'] ?? ($data['is_published'] ? now() : null),
            ]);

            if ($request->hasFile('cover')) {
                $file = $request->file('cover');
                $ext  = $file->getClientOriginalExtension();
                $safe = preg_replace(
                    '/[^A-Za-z0-9_\-]/',
                    '_',
                    pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)
                );

                $bucket = env('GCS_BUCKET', 'courtplay-storage');
                $key    = base_path(env('GCS_KEY_PATH', 'storage/app/keys/courtplay-gcs-key.json'));
                $object = "uploads/news/{$post->id}/{$safe}-" . time() . ".{$ext}";
                $public = upload_object($bucket, $object, $file->getPathname(), $key);

                $post->cover_url = $public;
                $post->save();
            }

            Log::info('Post created', ['admin_id' => auth()->id(), 'post_id' => $post->id]);
            toastr()->success('Post created successfully.');
            return redirect()->route('admin.posts.index');
        } catch (Throwable $e) {
            Log::error('Post creation failed', ['error' => $e->getMessage()]);
            toastr()->error('Failed to create post.');
            return back()->withInput();
        }
    }

    public function postsEdit(Post $post)
    {
        return view('admin.posts.form', compact('post'));
    }

    public function postsUpdate(Request $request, Post $post)
    {
        try {
            $data = $request->validate([
                'title'        => ['required', 'string', 'max:255'],
                'slug'         => ['nullable', 'string', 'max:255', Rule::unique('posts', 'slug')->ignore($post->id)],
                'excerpt'      => ['nullable', 'string', 'max:280'],
                'content'      => ['nullable', 'string'],
                'is_published' => ['nullable', 'boolean'],
                'published_at' => ['nullable', 'date'],
                'cover'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            ]);

            $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
            $data['is_published'] = (bool) ($data['is_published'] ?? false);

            $post->fill([
                'title'        => $data['title'],
                'slug'         => $data['slug'],
                'excerpt'      => $data['excerpt'] ?? null,
                'content'      => $data['content'] ?? null,
                'is_published' => $data['is_published'],
                'published_at' => $data['published_at'] ?? ($data['is_published']
                    ? ($post->published_at ?? now())
                    : null),
            ])->save();

            if ($request->hasFile('cover')) {
                $file = $request->file('cover');
                $ext  = $file->getClientOriginalExtension();
                $safe = preg_replace(
                    '/[^A-Za-z0-9_\-]/',
                    '_',
                    pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)
                );

                $bucket = env('GCS_BUCKET', 'courtplay-storage');
                $key    = base_path(env('GCS_KEY_PATH', 'storage/app/keys/courtplay-gcs-key.json'));
                $object = "uploads/news/{$post->id}/{$safe}-" . time() . ".{$ext}";
                $public = upload_object($bucket, $object, $file->getPathname(), $key);

                $post->cover_url = $public;
                $post->save();
            }

            Log::info('Post updated', ['admin_id' => auth()->id(), 'post_id' => $post->id]);
            toastr()->success('Post updated successfully.');
            return redirect()->route('admin.posts.index');
        } catch (Throwable $e) {
            Log::error('Post update failed', ['error' => $e->getMessage()]);
            toastr()->error('Failed to update post.');
            return back()->withInput();
        }
    }

    public function postsToggle(Post $post)
    {
        try {
            $post->is_published = !$post->is_published;
            if ($post->is_published && !$post->published_at) {
                $post->published_at = now();
            }
            $post->save();

            Log::info('Post toggled', [
                'admin_id' => auth()->id(),
                'post_id'  => $post->id,
                'status'   => $post->is_published,
            ]);

            toastr()->success('Post status updated.');
            return back();
        } catch (Throwable $e) {
            Log::error('Post toggle failed', ['error' => $e->getMessage()]);
            toastr()->error('Failed to toggle post.');
            return back();
        }
    }

    public function postsDestroy(Post $post)
    {
        try {
            $post->delete();

            Log::warning('Post deleted', ['admin_id' => auth()->id(), 'post_id' => $post->id]);
            toastr()->success('Post deleted successfully.');
            return back();
        } catch (Throwable $e) {
            Log::error('Post delete failed', ['error' => $e->getMessage()]);
            toastr()->error('Failed to delete post.');
            return back();
        }
    }
}
