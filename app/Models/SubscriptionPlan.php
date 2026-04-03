<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'price',
        'listing_limit',
        'featured_limit',
        'duration_days',
        'image_limit_per_vehicle',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'float',
            'listing_limit' => 'integer',
            'featured_limit' => 'integer',
            'duration_days' => 'integer',
            'image_limit_per_vehicle' => 'integer',
        ];
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
