<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'district',
        'postal_code',
        'items',
        'subtotal',
        'tax',
        'total',
        'payment_method',
        'payment_status',
        'order_status',
        'notes',
    ];

    protected $casts = [
        'items' => 'array',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the status history for the order.
     */
    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at', 'desc');
    }

    /**
     * Generate unique order number
     */
    public static function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $lastOrder = self::whereDate('created_at', today())->latest()->first();
        
        if ($lastOrder && $lastOrder->order_number) {
            $lastNumber = (int) substr($lastOrder->order_number, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }
        
        return 'ORD-' . $date . '-' . $newNumber;
    }

    /**
     * Get status label in Turkish
     */
    public function getStatusLabelAttribute(): string
    {
        $statuses = [
            'pending' => 'Beklemede',
            'processing' => 'İşleniyor',
            'preparing' => 'Hazırlanıyor',
            'shipped' => 'Kargoya Verildi',
            'delivered' => 'Teslim Edildi',
            'cancelled' => 'İptal Edildi',
        ];

        return $statuses[$this->order_status] ?? $this->order_status;
    }

    /**
     * Get payment status label in Turkish
     */
    public function getPaymentStatusLabelAttribute(): string
    {
        $statuses = [
            'pending' => 'Beklemede',
            'paid' => 'Ödendi',
            'failed' => 'Başarısız',
        ];

        return $statuses[$this->payment_status] ?? $this->payment_status;
    }
}
