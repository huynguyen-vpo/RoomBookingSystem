<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;

trait HasUuids
{
    protected static function boot() {
        parent::boot();
        static::creating(function ($model) {
            $model->id = (string)Str::uuid();
        });
    }
    public function getIncrementing(): bool {
        return false;
    }
    public function getKeyType(): string {
        return 'id';
    }
}
