<?php

namespace SaasPro\Features\Models;

use Illuminate\Database\Eloquent\Model;

class FeatureItem extends Model {
    
    protected $fillable = ['feature_id', 'limit', 'reset_period', 'reset_interval' ];

    function featurable(){
        return $this->morphTo();
    }

    function feature(){
        return $this->belongsTo(Feature::class, 'feature_id');
    }


}
