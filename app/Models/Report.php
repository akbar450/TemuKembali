<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Report extends Model
{
    public const ROLES = [
        'mahasiswa' => 'Mahasiswa',
        'dosen' => 'Dosen',
        'staf' => 'Staf',
        'tamu' => 'Tamu',
    ];

    protected $fillable = [
        'code',
        'claim_pin',
        'type',
        'status',
        'item_name',
        'category_id',
        'description',
        'secret_note',
        'location',
        'event_date',
        'reporter_name',
        'reporter_contact',
        'reporter_role',
        'reporter_id_number',
        'returned_at',
    ];

    public function getReporterRoleLabelAttribute(): string
    {
        return self::ROLES[$this->reporter_role] ?? ($this->reporter_role ? ucfirst($this->reporter_role) : '-');
    }

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

    public static function generateClaimPin(): string
    {
        return sprintf('%04d', random_int(1000, 9999));
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

