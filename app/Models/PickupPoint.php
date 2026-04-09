<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PickupPoint extends Model
{
    protected $fillable = [
        'name', 'address', 'city', 'postal_code',
        'description', 'contact_phone', 'active',
    ];

    protected function casts(): array
    {
        return ['active' => 'boolean'];
    }

    public function slots(): HasMany
    {
        return $this->hasMany(PickupSlot::class)->orderBy('day_of_week')->orderBy('open_time');
    }

    public function activeSlots(): HasMany
    {
        return $this->hasMany(PickupSlot::class)
            ->where('active', true)
            ->orderBy('day_of_week')
            ->orderBy('open_time');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getFullAddressAttribute(): string
    {
        return $this->address . ', ' . $this->postal_code . ' ' . $this->city;
    }
}
