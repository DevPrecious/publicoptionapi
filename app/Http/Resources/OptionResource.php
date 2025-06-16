<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    /**
     * The total number of votes across all options.
     * This is used to calculate percentages.
     *
     * @var int|null
     */
    public static $totalVotes = null;

    /**
     * Set the total votes for percentage calculation.
     *
     * @param int $totalVotes
     * @return void
     */
    public static function setTotalVotes(int $totalVotes): void
    {
        static::$totalVotes = $totalVotes;
    }

    public function toArray(Request $request): array
    {
        $votesCount = $this->votes_count ?? 0;
        $percentage = 0;
        
        if (static::$totalVotes > 0) {
            $percentage = round(($votesCount / static::$totalVotes) * 100, 2);
        }

        return [
            'id' => $this->id,
            'option_text' => $this->option_text,
            'option_image' => env('APP_URL') . $this->option_image,
            'votes_count' => $votesCount,
            'percentage' => $percentage,
            'vote_count' => $this->vote_count,
        ];
    }
}
