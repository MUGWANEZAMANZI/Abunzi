<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = ['file_path', 'file_type', 'dispute_id'];

    public function dispute() {
        return $this->belongsTo(Dispute::class);
    }
}

