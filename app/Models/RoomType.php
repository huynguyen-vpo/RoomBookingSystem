<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomType extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = ['type', 'capacity'];
    protected $casts = [
        'id' => 'string',
    ];
    public function rooms(): HasMany{
        return $this->hasMany(Room::class, 'room_typeid','id');
    }
    public function scopeCapacity(Builder $query, int $capcity): Builder{
        $current = 0;
        if($capcity <= 1) $current = 1;
        else if($capcity <= 2) $current = 2;
        else if($capcity <= 3) $current = 3;
        else $current = 4;
        
        return $query->where('capacity', $current)
                        ->orderByDesc('created_at');
    }
}
