<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'city',
        'address',
        'postal_code',
        'comment',
        'subtotal',
        'delivery_price',
        'total',
        'total_items',
        'status',
        'cancelled_at', // добавьте это
    ];

      protected $casts = [
        'cancelled_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->order_number = 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
     // Добавьте метод для проверки возможности отмены
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'processing']) && !$this->cancelled_at;
    }
}