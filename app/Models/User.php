<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    public const ROLE_USER = 'user';
    public const ROLE_ADMIN = 'admin';

    public const PROFILE_STATUS_DRAFT = 'draft';
    public const PROFILE_STATUS_PENDING = 'pending';
    public const PROFILE_STATUS_VERIFIED = 'verified';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'avatar_path',
        'institution',
        'study_program',
        'city',
        'student_id',
        'linkedin_url',
        'profile_verification_status',
        'profile_verified_at',
        'provider',
        'provider_id',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'string',
            'profile_verified_at' => 'datetime',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function orderReviews(): HasMany
    {
        return $this->hasMany(OrderReview::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Send the password reset notification using Mailtrap.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        // Instead of using Laravel's notification system,
        // we'll handle this through our Mailtrap service
        // The actual email sending will be handled by the password reset flow
        // For now, we'll just store the token or handle it as needed

        // You could send a custom notification here using Mailtrap service
        // But for simplicity, we'll let the password reset flow handle it
    }
}
