<?php

namespace SaasPro\Features\Concerns;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use SaasPro\Features\Facades\Features;
use SaasPro\Features\Models\Feature;

trait HasFeatureThrough {

    protected function featurePivotName(){
        return 'pivot';
    }

    protected function getFeaturePivotColumn(){
        return null;
    }

    function features(){
        if(!$pivotColumn = $this->getFeaturePivotColumn()) {
            throw new Exception("Property 'featurePivotColumn' must be defined in class ".$this::class);
        }

        $query = $this->belongsToMany(
            Feature::class, 
            'feature_items', 
            'featureable_id',
            'id',
            $pivotColumn
        )->as($this->featurePivotName());

        $query->withPivot(['limit', 'reset_period', 'reset_interval']);

        return $query;
    }

    function getFeature(string $name){
        return $this->features()
                    ->whereFeatureClass($name)
                    ->orWhere('shortcode', $name)
                    ->first();
    }

    function featureUsageQuery(string $name, $from = null, $to = null){
        $feature = $this->getFeature($name);
        return $feature?->history()
                ->whereHasMorph('owner', [$this::class])
                ->when($from, fn($query) => $query->where('created_at', '>=', $from))
                ->when($to, fn($query) => $query->where('created_at', '<=', $to));
    }

    function getFeatureUsage(string $name, $from = null, $to = null){
        return $this->featureUsageQuery($name)
                    ->get();
    }

    function getFeatureLimit(string $name){
        $feature = $this->getFeature($name);
        return $feature?->{$this->featurePivotName()}?->limit ?? $this->limit;
    }

}