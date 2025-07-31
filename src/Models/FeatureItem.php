<?php

namespace SaasPro\Features\Models;

use Illuminate\Database\Eloquent\Model;
use SaasPro\Enums\Timelines;

class FeatureItem extends Model {
    
    protected $fillable = ['feature_id', 'limit', 'reset_period', 'reset_interval' ];

    protected $casts = [
        'limit' => 'integer',
        'reset_period' => 'integer',
        'reset_interval' => Timelines::class,
    ];

    function featureable(){
        return $this->morphTo();
    }
    function feature(){
        return $this->belongsTo(Feature::class);
    }


}
