<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Feature;

class FeatureCategory extends Model
{
    protected $fillable = [
        'code',
        'sort_order',
        'is_active'
    ];

    public function features(): HasMany
    {
        return $this->hasMany(Feature::class);
    }
}
