<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = ['dispute_id', 'venue', 'meeting_time'];

    public function dispute() {
        return $this->belongsTo(Dispute::class);
    }
}
