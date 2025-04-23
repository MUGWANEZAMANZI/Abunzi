<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['dispute_id', 'file_path', 'generated_at'];

    public function dispute() {
        return $this->belongsTo(Dispute::class);
    }
}
