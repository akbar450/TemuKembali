<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name'];

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Urutkan kategori dengan "Lainnya" selalu berada di paling akhir.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByRaw("CASE WHEN name = 'Lainnya' THEN 1 ELSE 0 END, name ASC");
    }
}

