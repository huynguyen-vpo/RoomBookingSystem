<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookedRoomDay extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = "booked_room_days";

    protected $fillable = [
        "booking_id",
        "room_id", 
        "booking_date",
        "price_per_day",
    ];

    public function room(){
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function booking(){
        return $this->belongsTo(Booking::class, 'booking_id');
    }

}
