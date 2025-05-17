<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    
    protected $fillable = [
        'assignment_id',
        'dispute_id',
        'victim_resolution',
        'offender_resolution',
        'witnesses',
        'attendees',
        'justice_resolution',
        'evidence_path',
        'ended_at',
    ];

    public function dispute() {
        return $this->belongsTo(Dispute::class);
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    
}
