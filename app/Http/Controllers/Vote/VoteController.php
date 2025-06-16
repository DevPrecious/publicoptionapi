<?php

namespace App\Http\Controllers\Vote;

use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\Poll;
use App\Models\Vote;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function vote(Request $request, $unique_code)
    {
        try {
            $request->validate([
                'option_id' => 'required|exists:options,id',
            ]);

            $poll = Poll::where('unique_code', $unique_code)->first();
            if (!$poll) {
                return response([
                    'status' => 'error',
                    'message' => 'Poll not found'
                ], 404);
            }

            $option = Option::where('id', $request->option_id)
                ->where('poll_id', $poll->id)
                ->first();

            if (!$option) {
                return response([
                    'status' => 'error',
                    'message' => 'Invalid option'
                ], 400);
            }

            $ip = $request->ip();
            $alreadyVoted = Vote::where('poll_id', $poll->id)
                ->where('ip_address', $ip)
                ->exists();

            if ($alreadyVoted) {
                return response([
                    'status' => 'error',
                    'message' => 'You have already voted'
                ], 403);
            }

            Vote::create([
                'poll_id' => $poll->id,
                'option_id' => $option->id,
                'ip_address' => $ip,
            ]);
        
            $option->increment('vote_count');

            return response([
                'status' => 'success',
                'message' => 'Vote cast successfully',
                'data' => $option
            ], 200);
        } catch (\Exception $e) {
            return response([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
