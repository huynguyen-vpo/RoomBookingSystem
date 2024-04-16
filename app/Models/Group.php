<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = "groups";

    protected $fillable = [
        "name"
    ];

    public function users(){
        return $this->belongsToMany(User::class, 'user_group', 'group_id', 'user_id');
    }

    public function bookings(){
        return $this->morphMany(Booking::class, 'target');
    }
}
