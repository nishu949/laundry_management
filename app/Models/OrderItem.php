<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'rate_card_id',
        'count',
        'unit_rate',
        'line_total',
    ];

    protected $casts = [
        'unit_rate'  => 'float',
        'line_total' => 'float',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function rateCard()
    {
        return $this->belongsTo(RateCard::class);
    }

    public function getUnitRateFormattedAttribute(): string
    {
        return '$' . number_format($this->unit_rate, 2);
    }

    public function getLineTotalFormattedAttribute(): string
    {
        return '$' . number_format($this->line_total, 2);
    }
}