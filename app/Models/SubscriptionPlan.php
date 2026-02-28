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

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
