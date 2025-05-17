<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'is_verified',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
