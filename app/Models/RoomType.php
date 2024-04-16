<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomType extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = ['type', 'capacity'];
    protected $casts = [
        'id' => 'string',
    ];
    public function rooms(): BelongsTo{
        return $this->belongsTo(Room::class,'roomtype_id','id');
    }
}
