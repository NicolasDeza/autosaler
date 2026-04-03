<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\UserStatus;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'company_id',
        'first_name',
        'last_name',
        'email',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
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
            'two_factor_confirmed_at' => 'datetime',
            'status' => UserStatus::class,
            'company_id' => 'integer',
        ];
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function vehicleAds(): HasMany
    {
        return $this->hasMany(VehicleAd::class);
    }

    public function favoriteVehicleAds(): BelongsToMany
    {
        return $this->belongsToMany(
            VehicleAd::class,
            'user_vehicles',
            'user_id',
            'vehicle_ad_id',
        );
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription(): ?Subscription
    {
        return $this->subscriptions()->active()->first();
    }

    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription() !== null;
    }

    public function imageLimit(): int
    {
        if ($this->hasRole('admin')) {
            return 100; // Large enough limit for admins
        }

        return (int) ($this->activeSubscription()?->plan?->image_limit_per_vehicle ?? 0);
    }
}
