<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'user_id',
        'listings_used',
        'feature_used',
    ];

    protected function casts(): array
    {
        return [
            'subscription_id' => 'integer',
            'user_id' => 'integer',
            'listings_used' => 'integer',
            'feature_used' => 'integer',
        ];
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
