<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Report extends Model
{
    protected $fillable = [
        'code',
        'type',
        'status',
        'item_name',
        'category_id',
        'description',
        'location',
        'event_date',
        'reporter_name',
        'reporter_contact',
        'returned_at',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'returned_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public static function generateUniqueCode(): string
    {
        do {
            // format: TK-5 karakter huruf besar dan angka tanpa karakter ambigu
            $code = 'TK-' . strtoupper(Str::random(5));
        } while (static::where('code', $code)->exists());

        return $code;
    }

    public function allowedStatusTransitions(): array
    {
        return match ($this->status) {
            'hilang' => ['ditemukan', 'dikembalikan'],
            'ditemukan' => ['dikembalikan'],
            default => [],
        };
    }
}

