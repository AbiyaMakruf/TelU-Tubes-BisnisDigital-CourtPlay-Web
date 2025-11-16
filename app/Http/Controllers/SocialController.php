<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Follow;
use Illuminate\Support\Facades\DB;
use App\Models\Project;

class SocialController extends Controller
{
    /**
     * Menampilkan halaman sosial dengan 5 pengguna dengan followers terbanyak,
     * 5 pengguna terbaru, dan hasil pencarian berdasarkan username, first_name, atau last_name.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $searchTerm = $request->input('search', '');
        $userId = auth()->id();

        // --- Popular Users (EXCLUDE ADMIN) ---
        $topFollowers = User::select('users.*', 'follows.followers_count')
            ->leftJoin('follows', 'follows.user_id', '=', 'users.id')
            ->where('users.role', '!=', 'admin')
            ->orderByDesc('follows.followers_count')
            ->limit(5)
            ->get();

        function formatCount($num) {
            if ($num >= 1000000) {
                return round($num / 1000000, 1) . 'M';
            } elseif ($num >= 10000) {
                return round($num / 1000, 1) . 'K';
            } elseif ($num >= 1000) {
                return number_format($num);
            }
            return $num;
        }

        foreach ($topFollowers as $u) {
            $u->followers_count_formatted = formatCount($u->followers_count ?? 0);
        }

        // --- Latest Users (EXCLUDE ADMIN) ---
        $latestUsers = User::where('role', '!=', 'admin')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // --- Search Users (EXCLUDE ADMIN) ---
        $users = User::where('role', '!=', 'admin')
            ->where(function ($q) use ($searchTerm) {
                $q->where('username', 'like', "%$searchTerm%")
                ->orWhere('first_name', 'like', "%$searchTerm%")
                ->orWhere('last_name', 'like', "%$searchTerm%");
            })
            ->take(20)
            ->get();

        // --- Latest Projects ---
        $latestProjects = Project::join(
                'project_details',
                'projects.project_details_id',
                '=',
                'project_details.id'
            )
            ->with('user')
            ->select('projects.*', 'project_details.*')
            ->orderByDesc('projects.created_at')
            ->limit(10)
            ->get();

        // Hitung major movement
        foreach ($latestProjects as $p) {
            $fore = $p->forehand_count ?? 0;
            $back = $p->backhand_count ?? 0;

            $p->major_movement = $fore > $back ? 'Forehand'
                                : ($back > $fore ? 'Backhand' : 'Balanced');
        }

        // --- AJAX untuk live search ---
        if ($request->ajax()) {
            $html = view('partials.user-list', [
                'users' => $users,
                'userId' => $userId
            ])->render();

            return response()->json(['html' => $html]);
        }

        // --- Normal page load ---
        return view('social', [
            'topFollowers' => $topFollowers,
            'latestUsers' => $latestUsers,
            'users' => $users,
            'searchTerm' => $searchTerm,
            'userId' => $userId,
            'latestProjects' => $latestProjects
        ]);
    }


    /**
     * Menambahkan atau menghapus following pada pengguna yang di-follow
     *
     * @param string $username
     * @return \Illuminate\Http\RedirectResponse
     */
    // 

    public function follow($username)
    {
        // Mendapatkan pengguna yang di-follow berdasarkan username
        $userToFollow = User::where('username', $username)->firstOrFail();

        // Mendapatkan pengguna yang sedang login
        $user = auth()->user();

        if ($user->id === $userToFollow->id) {
            return response()->json(['success' => false, 'message' => 'You cannot follow yourself.']);
        }

        $isFollowing = false;  // Default follow status

        // Cek apakah pengguna yang sedang login sudah mengikuti pengguna ini menggunakan method isFollowing dari model Follow
        if (Follow::isFollowing($user->id, $userToFollow->id)) {
            // Jika sudah follow, lakukan unfollow
            $userFollow = Follow::where('user_id', $user->id)->first();
            $userFollow->removeFollowing($userToFollow->id);

            // Hapus follower dari pengguna yang di-follow
            $followedUser = Follow::where('user_id', $userToFollow->id)->first();
            $followedUser->removeFollower($user->id);

            $userFollow->updateFollowersCount($user->id);
            $followedUser->updateFollowersCount($userToFollow->id);
        } else {
            // Jika belum follow, lakukan follow
            $userFollow = Follow::where('user_id', $user->id)->first();
            if (!$userFollow) {
                // Jika pengguna belum memiliki entri di tabel follows, buat entri baru
                $userFollow = Follow::create([
                    'user_id' => $user->id,
                    'following' => json_encode([$userToFollow->id]),
                    'followers' => json_encode([]),
                ]);
            } else {
                $userFollow->addFollowing($userToFollow->id); // Menambahkan following
            }

            // Menambahkan follower ke dalam data user yang di-follow
            $followedUser = Follow::where('user_id', $userToFollow->id)->first();
            if (!$followedUser) {
                // Jika pengguna yang di-follow belum memiliki entri di tabel follows, buat entri baru
                $followedUser = Follow::create([
                    'user_id' => $userToFollow->id,
                    'followers' => json_encode([$user->id]),
                    'following' => json_encode([]),
                ]);
            } else {
                $followedUser->addFollower($user->id); // Menambahkan follower
            }

            // Menghitung ulang jumlah followers dan following
            $userFollow->updateFollowersCount($user->id);
            $followedUser->updateFollowersCount($userToFollow->id);

            // Set follow status to true after successful follow
            $isFollowing = true;
        }

        // Return the follow status and success as a JSON response
        return response()->json([
            'success' => true,
            'isFollowing' => $isFollowing,
        ]);
    }


}
