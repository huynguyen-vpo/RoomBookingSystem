<?php

namespace App\Models;

use App\Enums\RoomStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Room extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = ['room_number', 'status', 'room_typeid'];
    protected $casts = [
        'id' => 'string',
    ];
    public function type(): HasOne{
        return $this->hasOne(RoomType::class,'id','room_typeid');
    }
    public function scopeAvailable(Builder $query, RoomStatus $status): Builder{
        return $query->where('status', $status)
                        ->with('type')
                        ->orderByDesc('created_at');
    }
    public function scopeCapacity(Builder $query, int $capcity): Builder{
        $current = 0;
        if($capcity <= 1) $current = 1;
        else if($capcity <= 2) $current = 2;
        else if($capcity <= 3) $current = 3;
        else $current = 4;
        
        return $query->where('capacity', $current)
                        ->available()
                        ->with('type')
                        ->orderByDesc('created_at');
    }
}

