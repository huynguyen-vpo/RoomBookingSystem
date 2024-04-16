<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = "bookings";

    protected $fillable = [
        "check_in_date",
        "check_out_date", 
        "total_price"
    ];

    public function target(){
        return $this->morphTo();
    }
}
