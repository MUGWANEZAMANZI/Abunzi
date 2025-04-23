<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisputeStatus extends Model
{
    protected $fillable = ['dispute_id', 'status', 'updated_by'];

    public function dispute() {
        return $this->belongsTo(Dispute::class);
    }

    public function updater() {
        return $this->belongsTo(User::class, 'updated_by');
    }
}

