<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = ['dispute_id', 'justice_id', 'level'];

    public function dispute() {
        return $this->belongsTo(Dispute::class);
    }

    public function justice() {
        return $this->belongsTo(User::class, 'justice_id');
    }
}
