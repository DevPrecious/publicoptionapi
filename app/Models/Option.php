<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = [
        'poll_id',
        'option_text',
        'option_image',
        'vote_count',
    ];

    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }
}
