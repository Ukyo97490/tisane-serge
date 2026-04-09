<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'reference', 'customer_name', 'customer_email', 'customer_phone',
        'pickup_point_id', 'pickup_date', 'pickup_time',
        'total', 'status', 'notes',
        'reminder_24h_sent', 'reminder_1h_sent', 'archived_at',
    ];

    protected function casts(): array
    {
        return [
            'pickup_date'       => 'date',
            'total'             => 'decimal:2',
            'reminder_24h_sent' => 'boolean',
            'reminder_1h_sent'  => 'boolean',
            'archived_at'       => 'datetime',
        ];
    }

    public function scopeActive($query)
    {
        return $query->whereNull('archived_at');
    }

    public function scopeArchived($query)
    {
        return $query->whereNotNull('archived_at');
    }

    public function isArchived(): bool
    {
        return $this->archived_at !== null;
    }

    public function pickupPoint(): BelongsTo
    {
        return $this->belongsTo(PickupPoint::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateReference(): string
    {
        do {
            $ref = 'TL-' . strtoupper(substr(md5(uniqid()), 0, 8));
        } while (static::where('reference', $ref)->exists());

        return $ref;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'en_attente' => 'En attente',
            'confirmee'  => 'Confirmée',
            'prete'      => 'Prête',
            'recuperee'  => 'Récupérée',
            'annulee'    => 'Annulée',
            default      => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'en_attente' => 'amber',
            'confirmee'  => 'blue',
            'prete'      => 'green',
            'recuperee'  => 'gray',
            'annulee'    => 'red',
            default      => 'gray',
        };
    }
}
