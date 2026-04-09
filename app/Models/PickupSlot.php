<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PickupSlot extends Model
{
    protected $fillable = ['pickup_point_id', 'day_of_week', 'open_time', 'close_time', 'active'];

    protected function casts(): array
    {
        return ['active' => 'boolean'];
    }

    public function pickupPoint(): BelongsTo
    {
        return $this->belongsTo(PickupPoint::class);
    }

    public function getDayNameAttribute(): string
    {
        $days = [
            0 => 'Dimanche',
            1 => 'Lundi',
            2 => 'Mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
        ];
        return $days[$this->day_of_week] ?? '';
    }

    public function getFormattedHoursAttribute(): string
    {
        return substr($this->open_time, 0, 5) . ' - ' . substr($this->close_time, 0, 5);
    }
}
