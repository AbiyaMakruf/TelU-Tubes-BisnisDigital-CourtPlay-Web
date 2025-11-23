<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MatchmakingSearch;
use App\Models\MatchmakingMatch;
use App\Models\MatchmakingMatchPlayer;
use App\Models\MatchmakingMatchGame;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;



class MatchmakingController extends Controller
{
    public function index()
    {
        try {
            $user = auth()->user();

            // Ambil semua pencarian user
            $searches = MatchmakingSearch::where('user_id', $user->id)
                ->where('status', '!=', 'matched')
                ->select(
                    'id',
                    'court_id',
                    'user_id',
                    'play_mode',
                    'play_date',
                    'play_time_start',
                    'play_time_end',
                    'status',
                    'created_at'
                )
                ->with(['court', 'user'])
                ->get()
                ->map(function ($item) {
                    $item->type = 'search';
                    $ownerUsername = $item->user?->username;
                    $item->display_owner = $ownerUsername
                        ? '@' . ltrim($ownerUsername, '@')
                        : ($item->user?->name ?? null);
                    return $item;
                });


            // Ambil semua match user
            $matches = MatchmakingMatch::whereHas('players', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->with(['players.user', 'players.search.court', 'games'])
                ->get()
                ->map(function ($item) {
                    $item->type = 'match';
                    $item->display_title = $this->composeMatchTitle($item);
                    return $item;
                });

            // Gabungkan dan urutkan berdasarkan waktu
            $history = $searches
                ->concat($matches)
                ->sortByDesc('created_at')
                ->values();

            return view('matchmaking.index', compact('history'));
        } catch (Throwable $e) {
            Log::error('Failed to load matchmaking index', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);
            toastr()->error('Unable to load your matchmaking activity right now. Please try again.');
            return redirect()->back();
        }
    }


    public function create()
    {
        try {
            $courts = \App\Models\Court::where('status', 'active')->orderBy('name')->get();

            return view('matchmaking.search', compact('courts'));
        } catch (Throwable $e) {
            Log::error('Failed to load matchmaking search form', ['user_id' => auth()->id(), 'error' => $e->getMessage()]);
            toastr()->error('Unable to open the matchmaking form. Please try again.');
            return redirect()->route('matchmaking.index');
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'court_id'        => 'required|exists:courts,id',
                'play_mode'       => 'required|in:single,double',
                'play_date'       => 'required|date|after_or_equal:today',
                'play_time_start' => 'required',
                'play_time_end'   => 'required|after:play_time_start',
            ]);

            $search = \App\Models\MatchmakingSearch::create([
                'id'              => Str::uuid(),
                'user_id'         => auth()->id(),
                'court_id'        => $request->court_id,
                'play_mode'       => $request->play_mode,
                'play_date'       => $request->play_date,
                'play_time_start' => $request->play_time_start,
                'play_time_end'   => $request->play_time_end,
                'status'          => 'searching',
            ]);

            $matchStatus = $this->runMatchmaking($search);

            if ($matchStatus === 'matched') {
                toastr()->success('Great news! We found an opponent and created a match.');
            } else {
                toastr()->success('Search created successfully. We will notify you when a match is ready.');
            }

            return redirect()->route('matchmaking.index');
        } catch (ValidationException $e) {
            Log::warning('Matchmaking search validation failed', [
                'user_id' => auth()->id(),
                'errors' => $e->errors()
            ]);
            toastr()->error('Please review the highlighted fields and try again.');
            return back()->withErrors($e->errors())->withInput();
        } catch (QueryException $e) {
            Log::error('Database error while creating matchmaking search', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);
            toastr()->error('We could not save your search due to a database issue.');
            return back()->withInput();
        } catch (Throwable $e) {
            Log::error('Unexpected error while creating matchmaking search', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);
            toastr()->error('Something went wrong while creating your search. Please try again.');
            return back()->withInput();
        }
    }


    private function runMatchmaking(MatchmakingSearch $newSearch)
    {
        try {
            $candidate = \App\Models\MatchmakingSearch::where('status', 'searching')
                ->where('play_mode', $newSearch->play_mode)
                ->where('court_id', $newSearch->court_id)
                ->where('id', '!=', $newSearch->id)
                ->where(function ($q) use ($newSearch) {
                    $q->where('play_time_start', '<', $newSearch->play_time_end)
                    ->where('play_time_end', '>', $newSearch->play_time_start);
                })
                ->orderBy('created_at', 'asc')
                ->first();

            if (!$candidate) {
                return 'searching';
            }

            $match = \App\Models\MatchmakingMatch::create([
                'id'    => Str::uuid(),
                'mode'  => $newSearch->play_mode,
                'status'=> 'matched',
            ]);

            \App\Models\MatchmakingMatchPlayer::create([
                'id'                   => Str::uuid(),
                'matchmaking_match_id' => $match->id,
                'user_id'              => $newSearch->user_id,
                'team'                 => 1,
                'from_search_id'       => $newSearch->id
            ]);

            \App\Models\MatchmakingMatchPlayer::create([
                'id'                   => Str::uuid(),
                'matchmaking_match_id' => $match->id,
                'user_id'              => $candidate->user_id,
                'team'                 => 2,
                'from_search_id'       => $candidate->id
            ]);

            $newSearch->update(['status' => 'matched']);
            $candidate->update(['status' => 'matched']);

            return 'matched';
        } catch (Throwable $e) {
            Log::error('Matchmaking pairing failed', [
                'new_search_id' => $newSearch->id ?? null,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    private function composeMatchTitle(MatchmakingMatch $match): string
    {
        $referencePlayer = $match->players->first(function ($player) {
            return $player->search !== null;
        });

        if (!$referencePlayer || !$referencePlayer->search) {
            return 'Match #' . $match->id;
        }

        $search = $referencePlayer->search;
        $courtName = $search->court?->name;
        $dateLabel = $search->play_date ? Carbon::parse($search->play_date)->format('d M Y') : null;
        $timeLabel = $search->play_time_start ? Carbon::parse($search->play_time_start)->format('H:i') : null;

        $parts = collect([$courtName, $dateLabel, $timeLabel])->filter();

        return $parts->isNotEmpty()
            ? $parts->implode(' - ')
            : 'Match #' . $match->id;
    }


    public function detailSearch($id)
    {
        try {
            $search = \App\Models\MatchmakingSearch::with(['court', 'user'])
                ->where('id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            return view('matchmaking.detail-search', compact('search'));
        } catch (ModelNotFoundException $e) {
            Log::warning('Matchmaking search not found', ['search_id' => $id, 'user_id' => auth()->id()]);
            toastr()->error('We could not find that search anymore.');
            return redirect()->route('matchmaking.index');
        } catch (Throwable $e) {
            Log::error('Failed to load matchmaking search detail', ['search_id' => $id, 'user_id' => auth()->id(), 'error' => $e->getMessage()]);
            toastr()->error('Unable to load the search details right now.');
            return redirect()->route('matchmaking.index');
        }
    }

    public function detailMatch($id)
    {
        try {
            $match = \App\Models\MatchmakingMatch::with([
                    'players.user',
                    'players.search.court',
                    'games'
                ])
                ->whereHas('players', function ($q) {
                    $q->where('user_id', auth()->id());
                })
                ->where('id', $id)
                ->firstOrFail();

            $match->display_title = $this->composeMatchTitle($match);

            return view('matchmaking.detail-match', compact('match'));
        } catch (ModelNotFoundException $e) {
            Log::warning('Matchmaking match not found', ['match_id' => $id, 'user_id' => auth()->id()]);
            toastr()->error('Match details are no longer available.');
            return redirect()->route('matchmaking.index');
        } catch (Throwable $e) {
            Log::error('Failed to load matchmaking match detail', ['match_id' => $id, 'user_id' => auth()->id(), 'error' => $e->getMessage()]);
            toastr()->error('Unable to load the match details right now.');
            return redirect()->route('matchmaking.index');
        }
    }

    public function cancelSearch($id)
    {
        try {
            $search = MatchmakingSearch::where('id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            if ($search->status !== 'searching') {
                toastr()->warning('Only active searches can be cancelled.');
                return back();
            }

            $search->update([
                'status' => 'cancelled'
            ]);

            toastr()->success('Search cancelled successfully.');
            return back();
        } catch (ModelNotFoundException $e) {
            Log::warning('Attempted to cancel missing search', ['search_id' => $id, 'user_id' => auth()->id()]);
            toastr()->error('We could not find that search.');
            return redirect()->route('matchmaking.index');
        } catch (Throwable $e) {
            Log::error('Failed to cancel matchmaking search', ['search_id' => $id, 'user_id' => auth()->id(), 'error' => $e->getMessage()]);
            toastr()->error('Unable to cancel the search right now.');
            return back();
        }
    }



    public function cancelMatch($id)
    {
        try {
            $match = MatchmakingMatch::where('id', $id)
                ->whereHas('players', function ($q) {
                    $q->where('user_id', auth()->id());
                })
                ->firstOrFail();

            if ($match->status !== 'matched') {
                toastr()->warning('Only pending matches can be cancelled.');
                return back();
            }

            $match->update([
                'status' => 'cancelled'
            ]);

            foreach ($match->players as $player) {
                if ($player->from_search_id) {
                    MatchmakingSearch::where('id', $player->from_search_id)
                        ->update(['status' => 'cancelled']);
                }
            }

            toastr()->success('Match cancelled successfully.');
            return back();
        } catch (ModelNotFoundException $e) {
            Log::warning('Attempted to cancel missing match', ['match_id' => $id, 'user_id' => auth()->id()]);
            toastr()->error('We could not find that match.');
            return redirect()->route('matchmaking.index');
        } catch (Throwable $e) {
            Log::error('Failed to cancel match', ['match_id' => $id, 'user_id' => auth()->id(), 'error' => $e->getMessage()]);
            toastr()->error('Unable to cancel the match right now.');
            return back();
        }
    }

    public function startgame($id)
    {
        try {
            $match = MatchmakingMatch::where('id', $id)
                ->whereHas('players', fn($q) => $q->where('user_id', auth()->id()))
                ->firstOrFail();

            if ($match->status !== 'matched') {
                toastr()->warning('Only matched games can be started.');
                return back();
            }

            $match->update([
                'status' => 'started'
            ]);

            toastr()->success('Match started successfully.');
            return back();
        } catch (ModelNotFoundException $e) {
            Log::warning('Attempted to start missing match', ['match_id' => $id, 'user_id' => auth()->id()]);
            toastr()->error('We could not find that match.');
            return redirect()->route('matchmaking.index');
        } catch (Throwable $e) {
            Log::error('Failed to start match', ['match_id' => $id, 'user_id' => auth()->id(), 'error' => $e->getMessage()]);
            toastr()->error('Unable to start the match right now.');
            return back();
        }
    }

    public function finishMatch($id)
    {
        try {
            $match = MatchmakingMatch::where('id', $id)
                ->whereHas('players', fn($q) => $q->where('user_id', auth()->id()))
                ->firstOrFail();

            if ($match->status !== 'started') {
                toastr()->warning('Only running matches can be finished.');
                return back();
            }

            $match->update([
                'status' => 'done'
            ]);

            toastr()->success('Match marked as finished.');
            return back();
        } catch (ModelNotFoundException $e) {
            Log::warning('Attempted to finish missing match', ['match_id' => $id, 'user_id' => auth()->id()]);
            toastr()->error('We could not find that match.');
            return redirect()->route('matchmaking.index');
        } catch (Throwable $e) {
            Log::error('Failed to finish match', ['match_id' => $id, 'user_id' => auth()->id(), 'error' => $e->getMessage()]);
            toastr()->error('Unable to finish the match right now.');
            return back();
        }
    }

    public function createGame($id)
    {
        try {
            $match = MatchmakingMatch::with(['players.search.court'])
                ->where('id', $id)
                ->whereHas('players', fn($q) => $q->where('user_id', auth()->id()))
                ->firstOrFail();

            $match->display_title = $this->composeMatchTitle($match);

            return view('matchmaking.games.create', compact('match'));
        } catch (ModelNotFoundException $e) {
            Log::warning('Attempted to access create game for missing match', ['match_id' => $id, 'user_id' => auth()->id()]);
            toastr()->error('Match not found.');
            return redirect()->route('matchmaking.index');
        } catch (Throwable $e) {
            Log::error('Failed to load create game form', ['match_id' => $id, 'user_id' => auth()->id(), 'error' => $e->getMessage()]);
            toastr()->error('Unable to open the game form right now.');
            return redirect()->route('matchmaking.match.detail', $id);
        }
    }
    public function storeGame(Request $request, $id)
    {
        try {
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

            toastr()->success('Game added successfully.');
            return redirect()->route('matchmaking.match.detail', $match->id);
        } catch (ModelNotFoundException $e) {
            Log::warning('Attempted to add game for missing match', ['match_id' => $id, 'user_id' => auth()->id()]);
            toastr()->error('Match not found.');
            return redirect()->route('matchmaking.index');
        } catch (ValidationException $e) {
            Log::warning('Add game validation failed', ['match_id' => $id, 'user_id' => auth()->id(), 'errors' => $e->errors()]);
            toastr()->error('Please correct the game scores and try again.');
            return back()->withErrors($e->errors())->withInput();
        } catch (QueryException $e) {
            Log::error('Database error while adding game', ['match_id' => $id, 'user_id' => auth()->id(), 'error' => $e->getMessage()]);
            toastr()->error('Unable to save the game right now.');
            return back()->withInput();
        } catch (Throwable $e) {
            Log::error('Unexpected error while adding game', ['match_id' => $id, 'user_id' => auth()->id(), 'error' => $e->getMessage()]);
            toastr()->error('Something went wrong while adding the game.');
            return back()->withInput();
        }
    }

    public function editGame($match_id, $game_id)
    {
        try {
            $match = MatchmakingMatch::with(['players.search.court'])
                ->where('id', $match_id)
                ->whereHas('players', fn($q) => $q->where('user_id', auth()->id()))
                ->firstOrFail();

            $game = MatchmakingMatchGame::where('id', $game_id)
                ->where('matchmaking_match_id', $match->id)
                ->firstOrFail();

            $match->display_title = $this->composeMatchTitle($match);

            return view('matchmaking.games.edit', compact('match', 'game'));
        } catch (ModelNotFoundException $e) {
            Log::warning('Attempted to edit missing game', ['match_id' => $match_id, 'game_id' => $game_id, 'user_id' => auth()->id()]);
            toastr()->error('Game not found.');
            return redirect()->route('matchmaking.match.detail', $match_id);
        } catch (Throwable $e) {
            Log::error('Failed to load edit game form', ['match_id' => $match_id, 'game_id' => $game_id, 'user_id' => auth()->id(), 'error' => $e->getMessage()]);
            toastr()->error('Unable to open the game editor right now.');
            return redirect()->route('matchmaking.match.detail', $match_id);
        }
    }
    public function updateGame(Request $request, $match_id, $game_id)
    {
        try {
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

            $game->update([
                'game_number'  => $request->game_number,
                'team1_score'  => $request->team1_score,
                'team2_score'  => $request->team2_score,
            ]);

            toastr()->success('Game updated successfully.');
            return redirect()->route('matchmaking.match.detail', $match->id);
        } catch (ModelNotFoundException $e) {
            Log::warning('Attempted to update missing game', ['match_id' => $match_id, 'game_id' => $game_id, 'user_id' => auth()->id()]);
            toastr()->error('Game not found.');
            return redirect()->route('matchmaking.match.detail', $match_id);
        } catch (ValidationException $e) {
            Log::warning('Update game validation failed', ['match_id' => $match_id, 'game_id' => $game_id, 'user_id' => auth()->id(), 'errors' => $e->errors()]);
            toastr()->error('Please fix the game details before saving.');
            return back()->withErrors($e->errors())->withInput();
        } catch (QueryException $e) {
            Log::error('Database error while updating game', ['match_id' => $match_id, 'game_id' => $game_id, 'user_id' => auth()->id(), 'error' => $e->getMessage()]);
            toastr()->error('Unable to update the game right now.');
            return back()->withInput();
        } catch (Throwable $e) {
            Log::error('Unexpected error while updating game', ['match_id' => $match_id, 'game_id' => $game_id, 'user_id' => auth()->id(), 'error' => $e->getMessage()]);
            toastr()->error('Something went wrong while updating the game.');
            return back()->withInput();
        }
    }


    public function deleteGame($match_id, $game_id)
    {
        try {
            $match = MatchmakingMatch::where('id', $match_id)
                ->whereHas('players', fn($q) => $q->where('user_id', auth()->id()))
                ->firstOrFail();

            $game = MatchmakingMatchGame::where('id', $game_id)
                ->where('matchmaking_match_id', $match->id)
                ->firstOrFail();

            $game->delete();

            toastr()->success('Game removed successfully.');
            return back();
        } catch (ModelNotFoundException $e) {
            Log::warning('Attempted to delete missing game', ['match_id' => $match_id, 'game_id' => $game_id, 'user_id' => auth()->id()]);
            toastr()->error('Game not found.');
            return redirect()->route('matchmaking.match.detail', $match_id);
        } catch (Throwable $e) {
            Log::error('Failed to delete game', ['match_id' => $match_id, 'game_id' => $game_id, 'user_id' => auth()->id(), 'error' => $e->getMessage()]);
            toastr()->error('Unable to delete the game right now.');
            return back();
        }
    }










}
