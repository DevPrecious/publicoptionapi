<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class Vote extends Model
{
    protected $fillable = [
        'poll_id',
        'option_id',
        'ip_address',
        'user_agent',
        'device',
        'browser',
        'os'
    ];

    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }

    public function option()
    {
        return $this->belongsTo(Option::class);
    }

    protected static function booted()
    {
        static::creating(function ($vote) {
            $vote->ip_address = Request::ip();
            $vote->user_agent = Request::userAgent();
            // You can add more device/browser detection logic here if needed
        });
    }
}
