<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['name', 'type', 'parent_id'];

    public function parent() {
        return $this->belongsTo(Location::class, 'parent_id');
    }

    public function children() {
        return $this->hasMany(Location::class, 'parent_id');
    }

    public function users() {
        return $this->hasMany(User::class);
    }

    public function disputes() {
        return $this->hasMany(Dispute::class);
    }
}



?>