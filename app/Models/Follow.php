<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\Log;


class Follow extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'follows'; // Nama tabel jika tidak sesuai konvensi

    protected $fillable = ['user_id', 'followers', 'following'];

    protected $casts = [
        'followers' => 'array',
        'following' => 'array',
    ];


    /**
     * Relasi user yang memiliki follow
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // app/Models/Follow.php

   public function addFollower($followerId)
    {
        // Pastikan followers adalah array, jika bukan, decode dari JSON
        $followers = $this->followers ? json_decode($this->followers, true) : [];

        // Cek apakah $followerId sudah ada dalam array followers
        if (!in_array($followerId, $followers)) {
            // Menambahkan followerId ke dalam array followers jika belum ada
            $followers[] = $followerId;

            // Menyimpan kembali data followers sebagai JSON
            $this->followers = json_encode($followers);
            $this->save();
        }
    }


    /**
     * Menambahkan following ke dalam array following
     *
     * @param string $followingId
     * @return void
     */
    public function addFollowing($followingId)
    {
        // Pastikan following adalah array, jika bukan, decode dari JSON
        $following = $this->following ? json_decode($this->following, true) : [];

        // Cek apakah $followingId sudah ada dalam array following
        if (!in_array($followingId, $following)) {
            // Menambahkan followingId ke dalam array following jika belum ada
            $following[] = $followingId;

            // Menyimpan kembali data following sebagai JSON
            $this->following = json_encode($following);
            $this->save();
        }
    }


    /**
     * Menghapus follower dari array followers
     *
     * @param string $followerId
     * @return void
     */
    public function removeFollower($followerId)
    {
        // Mengubah followers menjadi array jika belum terkonversi
        $followers = $this->followers ? json_decode($this->followers, true) : [];

        // Menghapus followerId dari array followers
        $this->followers = json_encode(array_diff($followers, [$followerId]));

        // Menyimpan kembali data followers setelah dihapus
        $this->save();
    }


    /**
     * Menghapus following dari array following
     *
     * @param string $followingId
     * @return void
     */
    public function removeFollowing($followingId)
    {
        // Mengubah following menjadi array jika belum terkonversi
        $following = $this->following ? json_decode($this->following, true) : [];

        // Menghapus followingId dari array following
        $this->following = json_encode(array_diff($following, [$followingId]));

        // Menyimpan kembali data following setelah dihapus
        $this->save();
    }


    /**
     * Mengupdate jumlah followers dan following
     *
     * @return void
     */
    public function updateFollowersCount($userId)
    {
        // Mengambil data followers dan following dari pengguna dengan $userId
        $follow = Follow::where('user_id', $userId)->first();

        if (!$follow) {
            // Jika data follow tidak ditemukan, tidak perlu melanjutkan
            return;
        }

        // Mengubah followers dan following menjadi array, jika belum terkonversi
        $followers = $follow->followers ? json_decode($follow->followers, true) : [];
        $following = $follow->following ? json_decode($follow->following, true) : [];

        // Mengupdate jumlah followers dan following
        $this->followers_count = count($followers);
        $this->following_count = count($following);

        // Debugging untuk memastikan bahwa followers dan following sudah benar
        // dd($followers, $following, $this->followers_count , $this->following_count);

        // Menyimpan kembali data followers_count dan following_count
        $this->save();
    }

     /**
     * Menentukan apakah pengguna sudah mengikuti pengguna lain.
     *
     * @param string $userId
     * @param string $followerId
     * @return bool
     */
    public static function isFollowing($userId, $followerId)
    {
        // Query untuk mengecek apakah pengguna $userId mengikuti $followerId
        return Follow::where('user_id', $userId)
                   ->whereRaw('CAST(following AS text) LIKE ?', ['%' . $followerId . '%'])
                   ->exists();
    }


}
