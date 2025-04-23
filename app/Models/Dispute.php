<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dispute extends Model
{
    protected $fillable = ['title', 'content', 'offender_name', 'status', 'citizen_id', 'location_id'];

    public function citizen() {
        return $this->belongsTo(User::class, 'citizen_id');
    }

    public function location() {
        return $this->belongsTo(Location::class);
    }

    public function witnesses() {
        return $this->hasMany(Witness::class);
    }

    public function files() {
        return $this->hasMany(File::class);
    }

    public function assignments() {
        return $this->hasMany(Assignment::class);
    }

    public function meeting() {
        return $this->hasOne(Meeting::class);
    }

    public function statuses() {
        return $this->hasMany(DisputeStatus::class);
    }

    public function report() {
        return $this->hasOne(Report::class);
    }
}
