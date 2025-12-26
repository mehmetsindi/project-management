<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    protected $fillable = [
        'task_id',
        'carrier',
        'tracking_number',
        'origin',
        'destination',
        'estimated_delivery',
        'actual_delivery',
        'status',
        'shipping_cost',
        'weight',
        'notes',
    ];

    protected $casts = [
        'estimated_delivery' => 'date',
        'actual_delivery' => 'date',
        'shipping_cost' => 'decimal:2',
        'weight' => 'decimal:2',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PICKED_UP = 'picked_up';
    const STATUS_IN_TRANSIT = 'in_transit';
    const STATUS_OUT_FOR_DELIVERY = 'out_for_delivery';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_EXCEPTION = 'exception';

    /**
     * Get the task that owns the shipment
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Pending Pickup',
            self::STATUS_PICKED_UP => 'Picked Up',
            self::STATUS_IN_TRANSIT => 'In Transit',
            self::STATUS_OUT_FOR_DELIVERY => 'Out for Delivery',
            self::STATUS_DELIVERED => 'Delivered',
            self::STATUS_EXCEPTION => 'Exception',
            default => 'Unknown',
        };
    }

    /**
     * Check if shipment is delivered
     */
    public function isDelivered(): bool
    {
        return $this->status === self::STATUS_DELIVERED;
    }

    /**
     * Check if shipment is in progress
     */
    public function isInProgress(): bool
    {
        return in_array($this->status, [
            self::STATUS_PICKED_UP,
            self::STATUS_IN_TRANSIT,
            self::STATUS_OUT_FOR_DELIVERY,
        ]);
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'gray',
            self::STATUS_PICKED_UP => 'blue',
            self::STATUS_IN_TRANSIT => 'indigo',
            self::STATUS_OUT_FOR_DELIVERY => 'purple',
            self::STATUS_DELIVERED => 'green',
            self::STATUS_EXCEPTION => 'red',
            default => 'gray',
        };
    }
}
