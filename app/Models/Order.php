<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_phone',
        'customer_address',
        'service_type',
        'status',
        'payment_status',
        'pickup_scheduled_at',
        'total_amount',
    ];

    protected $casts = [
        'pickup_scheduled_at'  => 'datetime',
        'picked_up_at'         => 'datetime',
        'washing_started_at'   => 'datetime',
        'washing_completed_at' => 'datetime',
        'ready_at'             => 'datetime',
        'delivered_at'         => 'datetime',
        'total_amount'         => 'float',
    ];

    const STATUS_PICKUP    = 'pickup';
    const STATUS_WASHING   = 'washing';
    const STATUS_READY     = 'ready';
    const STATUS_DELIVERED = 'delivered';

    public static function statuses(): array
    {
        return [
            self::STATUS_PICKUP,
            self::STATUS_WASHING,
            self::STATUS_READY,
            self::STATUS_DELIVERED,
        ];
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function calculateTotal(): float
    {
        return (float) $this->items()->sum('line_total');
    }

    public function updateStatus(string $status): void
    {
        $this->status = $status;

        $field = match ($status) {
            self::STATUS_PICKUP    => 'picked_up_at',
            self::STATUS_WASHING   => 'washing_started_at',
            self::STATUS_READY     => 'ready_at',
            self::STATUS_DELIVERED => 'delivered_at',
            default                => null,
        };

        if ($field) {
            $this->{$field} = now();
        }

        $this->save();
    }

    /* ---------- Accessors ---------- */

    public function getTotalFormattedAttribute(): string
    {
        return '$' . number_format($this->total_amount, 2);
    }

    public function getServiceLabelAttribute(): string
    {
        return match ($this->service_type) {
            'wash_fold'  => 'Wash & Fold',
            'dry_clean'  => 'Dry Clean',
            'iron_only'  => 'Iron Only',
            default      => ucwords(str_replace('_', ' ', $this->service_type)),
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return ucfirst($this->status);
    }

    public function getPaymentLabelAttribute(): string
    {
        return $this->payment_status === 'paid' ? 'Paid' : 'Pending';
    }

    public function getItemsCountAttribute(): int
    {
        return (int) $this->items->sum('count');
    }
}