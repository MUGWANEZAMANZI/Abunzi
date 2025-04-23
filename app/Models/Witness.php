<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Witness extends Model
{
    protected $fillable = ['name', 'phone', 'dispute_id'];

    public function dispute() {
        return $this->belongsTo(Dispute::class);
    }
}
