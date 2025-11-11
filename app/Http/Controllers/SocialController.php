<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Follow;
use Illuminate\Support\Facades\DB;

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
        // Mendapatkan query pencarian dari form
        $searchTerm = $request->input('search', '');

       $userId = auth()->user()->id;

        // Query untuk mendapatkan 5 user dengan followers terbanyak
        $topFollowers = User::select('users.*', 'follows.followers_count', 'follows.following_count')  // Ambil followers_count dan following_count dari tabel follows
                    ->leftJoin('follows', 'follows.user_id', '=', 'users.id')  // Join dengan tabel follows untuk mendapatkan followers_count dan following_count
                    ->orderByDesc('follows.followers_count')  // Urutkan berdasarkan followers_count yang ada di follows
                    ->limit(5)  // Ambil 5 pengguna teratas
                    ->get();


        // Query untuk mendapatkan 5 pengguna terbaru
        $latestUsers = User::orderByDesc('created_at')  // Urutkan berdasarkan waktu pembuatan
            ->limit(5)  // Ambil 5 pengguna terbaru
            ->get();

        // Query pencarian berdasarkan username, first_name, atau last_name
        $users = User::where('username', 'like', "%$searchTerm%")
            ->orWhere('first_name', 'like', "%$searchTerm%")
            ->orWhere('last_name', 'like', "%$searchTerm%")
            ->get();


        // dd($topFollowers);
        // Menampilkan halaman dengan data yang sudah diambil, termasuk user_id
        return view('social', compact('topFollowers', 'latestUsers', 'users', 'searchTerm', 'userId'));
    }

    /**
     * Menambahkan atau menghapus following pada pengguna yang di-follow
     *
     * @param string $username
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleFollow($username)
    {
        // Mendapatkan pengguna yang di-follow berdasarkan username
        $userToFollow = User::where('username', $username)->firstOrFail();

        // Mendapatkan pengguna yang sedang login
        $user = auth()->user();

        // Cek apakah pengguna yang sedang login sudah mengikuti pengguna ini menggunakan method isFollowing dari model Follow
        if (Follow::isFollowing($user->id, $userToFollow->id)) {
            // Jika sudah follow, lakukan unfollow
            $userFollow = Follow::where('user_id', $user->id)->first();
            $userFollow->removeFollowing($userToFollow->id);

            // Hapus follower dari pengguna yang di-follow
            $followedUser = Follow::where('user_id', $userToFollow->id)->first();
            $followedUser->removeFollower($user->id);

            $followedUser->updateFollowersCount($userToFollow->id);
            $userFollow->updateFollowersCount($user->id);
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
            $userFollow->updateFollowersCount( $user->id);
            $followedUser->updateFollowersCount( $userToFollow->id);
        }

        $userId = auth()->user()->id;
        // Mendapatkan data untuk halaman social
        $topFollowers = User::select('users.*', 'follows.followers_count', 'follows.following_count')  // Ambil followers_count dan following_count dari tabel follows
                    ->leftJoin('follows', 'follows.user_id', '=', 'users.id')  // Join dengan tabel follows untuk mendapatkan followers_count dan following_count
                    ->orderByDesc('follows.followers_count')  // Urutkan berdasarkan followers_count yang ada di follows
                    ->limit(5)  // Ambil 5 pengguna teratas
                    ->get();

        $latestUsers = User::orderByDesc('created_at')  // Urutkan berdasarkan waktu pembuatan
            ->limit(5)  // Ambil 5 pengguna terbaru
            ->get();

        // Query pencarian berdasarkan username, first_name, atau last_name
        $searchTerm = request()->get('search', '');
        $users = User::where('username', 'like', "%$searchTerm%")
            ->orWhere('first_name', 'like', "%$searchTerm%")
            ->orWhere('last_name', 'like', "%$searchTerm%")
            ->get();


        // Mengarahkan kembali ke halaman social dengan data yang diperlukan
        return view('social', compact('topFollowers', 'latestUsers', 'users', 'searchTerm', 'userId'));
    }

    public function follow($username)
    {
        // Mendapatkan pengguna yang di-follow berdasarkan username
        $userToFollow = User::where('username', $username)->firstOrFail();

        // Mendapatkan pengguna yang sedang login
        $user = auth()->user();

        // Cek apakah pengguna yang sedang login sudah mengikuti pengguna ini menggunakan method isFollowing dari model Follow
        if (Follow::isFollowing($user->id, $userToFollow->id)) {
            // Jika sudah follow, lakukan unfollow
            $userFollow = Follow::where('user_id', $user->id)->first();
            $userFollow->removeFollowing($userToFollow->id);

            // Hapus follower dari pengguna yang di-follow
            $followedUser = Follow::where('user_id', $userToFollow->id)->first();
            $followedUser->removeFollower($user->id);

            $userFollow->updateFollowersCount( $user->id);
            $followedUser->updateFollowersCount( $userToFollow->id);
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
            $userFollow->updateFollowersCount( $user->id);
            $followedUser->updateFollowersCount( $userToFollow->id);
        }

        // Mengarahkan kembali ke halaman sebelumnya setelah follow/unfollow
        return back();
    }


}
