<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RateCard extends Model
{
    protected $fillable = ['name', 'service_type', 'base_rate', 'tiers'];

    protected $casts = [
        'base_rate' => 'float',
        'tiers'     => 'array',
    ];

    /**
     * Return the rate per item for a given quantity.
     * Tiers are sorted descending by min_quantity, so we pick
     * the highest tier whose min_quantity the given quantity meets.
     */
    public function getRateForQuantity(int $quantity): float
    {
        $tiers = $this->tiers ?? [];

        usort($tiers, fn ($a, $b) => $b['min_quantity'] <=> $a['min_quantity']);

        foreach ($tiers as $tier) {
            if ($quantity >= $tier['min_quantity']) {
                return (float) $tier['rate'];
            }
        }

        return (float) $this->base_rate;
    }

    public function getBaseRateFormattedAttribute(): string
    {
        return '$' . number_format($this->base_rate, 2);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}