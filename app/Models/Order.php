<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total',
        'status',
        'checkout_token',
        'payment_method',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending_payment' => 'Pendiente de pago',
            'completed' => 'Completado',
            'cancelled' => 'Cancelado',
            default => 'Pendiente',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return $this->status === 'pending_payment' ? 'is-pending' : '';
    }
}
