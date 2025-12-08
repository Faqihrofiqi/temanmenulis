<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'order_number',
        'service_id',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'order_details',
        'amount',
        'status',
        'payment_status',
        'payment_token',
        'paid_at',
        'requires_followup',
        'internal_notes',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'requires_followup' => 'boolean',
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Order $order): void {
            if (empty($order->order_number)) {
                $order->order_number = 'DL-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
            }
        });
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function scopeVisibleFor($query, ?User $user)
    {
        if (! $user || $user->isAdmin()) {
            return $query;
        }

        return $query->where('user_id', $user->id)
            ->orWhere('customer_email', $user->email);
    }

    public function getRouteKeyName(): string
    {
        return 'order_number';
    }
}
