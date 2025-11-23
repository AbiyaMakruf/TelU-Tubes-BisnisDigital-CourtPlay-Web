<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MatchmakingSearch;
use App\Models\MatchmakingMatch;
use App\Models\MatchmakingMatchPlayer;
use App\Models\MatchmakingMatchGame;
use Illuminate\Support\Str;



class MatchmakingController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Ambil semua pencarian user
        $searches = MatchmakingSearch::where('user_id', $user->id)
            ->where('status', '!=', 'matched')   // ⬅ HILANGKAN SEARCH YANG SUDAH KETEMU
            ->select(
                'id',
                'court_id',
                'play_mode',
                'play_date',
                'play_time_start',
                'play_time_end',
                'status',
                'created_at'
            )
            ->with('court')
            ->get()
            ->map(function ($item) {
                $item->type = 'search';
                return $item;
            });


        // Ambil semua match user
        $matches = MatchmakingMatch::whereHas('players', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->with(['players.user', 'games'])
            ->get()
            ->map(function ($item) {
                $item->type = 'match'; // tag item
                return $item;
            });

        // Gabungkan dan urutkan berdasarkan waktu
        $history = $searches
            ->concat($matches)
            ->sortByDesc('created_at')
            ->values(); // reset index

        return view('matchmaking.index', compact('history'));
    }


    public function create()
    {
        $courts = \App\Models\Court::where('status', 'active')->orderBy('name')->get();

        return view('matchmaking.search', compact('courts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'court_id'        => 'required|exists:courts,id',
            'play_mode'       => 'required|in:single,double',
            'play_date'       => 'required|date|after_or_equal:today',
            'play_time_start' => 'required',
            'play_time_end'   => 'required|after:play_time_start',
        ]);

        $search = \App\Models\MatchmakingSearch::create([
            'id'              => \Str::uuid(),
            'user_id'         => auth()->id(),
            'court_id'        => $request->court_id,
            'play_mode'       => $request->play_mode,
            'play_date'       => $request->play_date,
            'play_time_start' => $request->play_time_start,
            'play_time_end'   => $request->play_time_end,
            'status'          => 'searching',
        ]);

        // Panggil MATCHMAKING langsung dari controller
        $matchStatus = $this->runMatchmaking($search);

        return redirect()->route('matchmaking.index')
            ->with('success', $matchStatus === 'matched'
                ? 'Ditemukan lawan! Match berhasil dibuat.'
                : 'Pencarian dibuat, menunggu lawan...'
            );
    }


    private function runMatchmaking($newSearch)
    {
        // CARI KANDIDAT YANG SESUAI
        $candidate = \App\Models\MatchmakingSearch::where('status', 'searching')
            ->where('play_mode', $newSearch->play_mode)
            ->where('court_id', $newSearch->court_id)
            ->where('id', '!=', $newSearch->id)
            ->where(function ($q) use ($newSearch) {
                // Overlap waktu
                $q->where('play_time_start', '<', $newSearch->play_time_end)
                ->where('play_time_end', '>', $newSearch->play_time_start);
            })
            ->orderBy('created_at', 'asc')
            ->first();

        // Jika tidak ada yang cocok → tetap searching
        if (!$candidate) {
            return 'searching';
        }

        // Jika ketemu kandidat → buat match
        $match = \App\Models\MatchmakingMatch::create([
            'id'    => \Str::uuid(),
            'mode'  => $newSearch->play_mode,
            'status'=> 'matched',
        ]);

        // Tambah player A (yang baru)
        \App\Models\MatchmakingMatchPlayer::create([
            'id'                   => \Str::uuid(),
            'matchmaking_match_id' => $match->id,
            'user_id'              => $newSearch->user_id,
            'team'                 => 1,
            'from_search_id'       => $newSearch->id
        ]);

        // Tambah player B (kandidat)
        \App\Models\MatchmakingMatchPlayer::create([
            'id'                   => \Str::uuid(),
            'matchmaking_match_id' => $match->id,
            'user_id'              => $candidate->user_id,
            'team'                 => 2,
            'from_search_id'       => $candidate->id
        ]);

        // Update status pencarian
        $newSearch->update(['status' => 'matched']);
        $candidate->update(['status' => 'matched']);

        return 'matched';
    }


    public function detailSearch($id)
    {
        $search = \App\Models\MatchmakingSearch::with(['court', 'user'])
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('matchmaking.detail-search', compact('search'));
    }

    public function detailMatch($id)
    {
        $match = \App\Models\MatchmakingMatch::with([
                'players.user',
                'games'
            ])
            ->whereHas('players', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->where('id', $id)
            ->firstOrFail();

        return view('matchmaking.detail-match', compact('match'));
    }

    public function cancelSearch($id)
    {
        $search = MatchmakingSearch::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($search->status !== 'searching') {
            return back()->with('error', 'Pencarian tidak dapat dibatalkan.');
        }

        $search->update([
            'status' => 'cancelled'
        ]);

        return back()->with('success', 'Pencarian berhasil dibatalkan.');
    }



    public function cancelMatch($id)
    {
        $match = MatchmakingMatch::where('id', $id)
            ->whereHas('players', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->firstOrFail();

        if ($match->status !== 'matched') {
            return back()->with('error', 'Match tidak dapat dibatalkan.');
        }

        // Update status match
        $match->update([
            'status' => 'cancelled'
        ]);

        // Update semua search yang menjadi bagian match ini
        foreach ($match->players as $player) {
            if ($player->from_search_id) {
                MatchmakingSearch::where('id', $player->from_search_id)
                    ->update(['status' => 'cancelled']);
            }
        }

        return back()->with('success', 'Match berhasil dibatalkan.');
    }

    public function startgame($id)
    {
        $match = MatchmakingMatch::where('id', $id)
            ->whereHas('players', fn($q) => $q->where('user_id', auth()->id()))
            ->firstOrFail();

        if (!in_array($match->status, ['matched'])) {
            return back()->with('error', 'Match tidak bisa dimulai.');
        }

        $match->update([
            'status' => 'started'
        ]);

        return back()->with('success', 'Match berhasil dimulai.');
    }

    public function finishMatch($id)
    {
        $match = MatchmakingMatch::where('id', $id)
            ->whereHas('players', fn($q) => $q->where('user_id', auth()->id()))
            ->firstOrFail();

        if (!in_array($match->status, ['started'])) {
            return back()->with('error', 'Match tidak bisa diselesaikan karena belum dimulai.');
        }

        $match->update([
            'status' => 'done'
        ]);

        return back()->with('success', 'Match berhasil ditandai selesai.');
    }

    public function createGame($id)
    {
        $match = MatchmakingMatch::where('id', $id)
            ->whereHas('players', fn($q) => $q->where('user_id', auth()->id()))
            ->firstOrFail();

        return view('matchmaking.games.create', compact('match'));
    }
    public function storeGame(Request $request, $id)
    {
        $match = MatchmakingMatch::where('id', $id)
            ->whereHas('players', fn($q) => $q->where('user_id', auth()->id()))
            ->firstOrFail();

        $request->validate([
            'team1_score' => 'required|integer|min:0',
            'team2_score' => 'required|integer|min:0',
        ]);

        $nextNumber = ($match->games->max('game_number') ?? 0) + 1;

        MatchmakingMatchGame::create([
            'id'                   => Str::uuid(),
            'matchmaking_match_id' => $match->id,
            'game_number'          => $nextNumber,
            'team1_score'          => $request->team1_score,
            'team2_score'          => $request->team2_score,
        ]);

        return redirect()->route('matchmaking.match.detail', $match->id)
            ->with('success', 'Game berhasil ditambahkan.');
    }

    public function editGame($match_id, $game_id)
    {
        $match = MatchmakingMatch::where('id', $match_id)
            ->whereHas('players', fn($q) => $q->where('user_id', auth()->id()))
            ->firstOrFail();

        $game = MatchmakingMatchGame::where('id', $game_id)
            ->where('matchmaking_match_id', $match->id)
            ->firstOrFail();

        return view('matchmaking.games.edit', compact('match', 'game'));
    }
    public function updateGame(Request $request, $match_id, $game_id)
    {
        $match = MatchmakingMatch::where('id', $match_id)
            ->whereHas('players', fn($q) => $q->where('user_id', auth()->id()))
            ->firstOrFail();

        $game = MatchmakingMatchGame::where('id', $game_id)
            ->where('matchmaking_match_id', $match->id)
            ->firstOrFail();

        $request->validate([
            'game_number'  => 'required|integer|min:1',
            'team1_score'  => 'required|integer|min:0',
            'team2_score'  => 'required|integer|min:0',
        ]);

        // allow renumbering
        $game->update([
            'game_number'  => $request->game_number,
            'team1_score'  => $request->team1_score,
            'team2_score'  => $request->team2_score,
        ]);

        return redirect()
            ->route('matchmaking.match.detail', $match->id)
            ->with('success', 'Game berhasil diupdate.');
    }


    public function deleteGame($match_id, $game_id)
    {
        $match = MatchmakingMatch::where('id', $match_id)
            ->whereHas('players', fn($q) => $q->where('user_id', auth()->id()))
            ->firstOrFail();

        $game = MatchmakingMatchGame::where('id', $game_id)
            ->where('matchmaking_match_id', $match->id)
            ->firstOrFail();

        $game->delete();

        return back()->with('success', 'Game berhasil dihapus.');
    }










}
