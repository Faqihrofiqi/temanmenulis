<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Service extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'short_description',
        'description',
        'delivery_days',
        'price',
        'discount_percentage',
        'discount_label',
        'discount_ends_at',
        'features',
        'thumbnail_path',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_ends_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Service $service): void {
            if (empty($service->slug)) {
                $service->slug = Str::slug($service->name).'-'.Str::random(4);
            }
        });
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function hasActiveDiscount(): bool
    {
        if (! $this->discount_percentage || $this->discount_percentage <= 0) {
            return false;
        }

        if ($this->discount_ends_at && $this->discount_ends_at->isPast()) {
            return false;
        }

        return true;
    }

    public function getEffectivePriceAttribute(): float
    {
        if (! $this->hasActiveDiscount()) {
            return (float) $this->price;
        }

        $discountAmount = (float) $this->price * ((float) $this->discount_percentage / 100);

        return max((float) $this->price - $discountAmount, 0.0);
    }

    public function getHasActiveDiscountAttribute(): bool
    {
        return $this->hasActiveDiscount();
    }
}
