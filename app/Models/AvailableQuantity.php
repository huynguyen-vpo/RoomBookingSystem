<?php

namespace App\Models;

use App\Traits\HasUuids;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailableQuantity extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = ['date', 'single_remaining_quantity', 'double_remaining_quantity', 'triple_remaining_quantity', 'quarter_remaining_quantity'];
    protected $casts = [
        'id' => 'string',
        'date' => 'datetime'
    ];
    function scopeLastestDate(Builder $query){
        return $query->latest('date')->first();
    }
}
