<?php

namespace SaasPro\Features\Models;

use Illuminate\Database\Eloquent\Model;

class FeatureUsage extends Model {
    
    protected $fillable = ['feature_id', 'meta', 'value', 'count'];

    public function feature(){
        return $this->belongsTo(Feature::class);
    }

    public function user(){
        return $this->morphTo();
    }

    public function owner(){
        return $this->morphTo();
    }

}
