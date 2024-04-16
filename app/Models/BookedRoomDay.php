<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

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

    public function scopeBookedRoom(Builder $query, mixed $roomId, DateTime $date){
        return $query
            ->where('room_id', $roomId)
            ->whereDate('booking_date', '=', $date)
            ->with('room');
    }
}
