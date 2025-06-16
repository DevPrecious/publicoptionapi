<?php

namespace App\Http\Controllers\Poll;

use App\Http\Controllers\Controller;
use App\Http\Resources\PollResource;
use App\Models\Poll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PollController extends Controller
{
    public function createPoll(Request $request)
    {
        try {
            $request->validate([
                'question' => 'required|string',
                'options' => 'required|array|min:2',
                'options.*' => 'required|array',
                'options.*.text' => 'nullable|string',
                'options.*.image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            ]);

            DB::beginTransaction();

            $uniqueId = Str::random(6);
            $poll = Poll::create([
                'question' => $request->question,
                'unique_code' => $uniqueId,
                'is_active' => true,
            ]);

            foreach ($request->options as $option) {
                $image = $option['image'] ?? null;
                if ($image) {
                    $imageName = time() . '_' . uniqid() . '.' . $image->extension();
                    $image->move(public_path('images'), $imageName);
                    $imagePath = 'images/' . $imageName;
                } else {
                    $imagePath = null;
                }

                $poll->options()->create([
                    'option_text' => $option['text'] ?? null,
                    'option_image' => $imagePath,
                    'vote_count' => 0,
                ]);
            }

            DB::commit();

            return response([
                'status' => 'success',
                'message' => 'Poll created successfully',
                'data' => $poll
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function viewPoll(Request $request)
    {
        try {
            $poll = Poll::where('unique_code', $request->unique_code)->first();
            if (!$poll) {
                return response([
                    'status' => 'error',
                    'message' => 'Poll not found'
                ], 404);
            }

            return response([
                'status' => 'success',
                'data' => PollResource::make($poll)
            ], 200);
        } catch (\Exception $e) {
            return response([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
