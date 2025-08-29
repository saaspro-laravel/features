<?php

namespace SaasPro\Features\Support;

use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use SaasPro\Features\Contracts\InteractsWithFeatures;
use SaasPro\Features\Models\Feature;
use SaasPro\Features\Models\FeatureUsage;

class Usage {

    protected ?Model $owner = null;
    protected array $meta = [];
    public Collection | null $history = null;

    function __construct(private ?Feature $feature = null, protected ?InteractsWithFeatures $user = null) {
        $this->feature = $feature->load('history');
        $this->history = $this->history();
    }

    static function for(Feature $feature, ?InteractsWithFeatures $user = null) {
        return new self($feature, $user);
    }

    function withMeta(array $meta) {
        $this->meta = $meta;
        return $this;
    }

    function forFeature(Feature $feature) {
        $this->feature = $feature;
        return $this;
    }

    function forUser(InteractsWithFeatures $user){
        $this->user = $user;
        return $this;
    }
    
    function forOwner(Model $owner){
        $this->owner = $owner;
        return $this;
    }

    function onAfterUsage(callable $callback) {
        $callback();
    }

    function history(){
        return $this->feature->history()->when($this->user, fn($query, $user) => $query->whereRelation('user', 'id', $user->id))->when($this->owner, fn($query, $owner) => $query->whereRelation('owner', 'id', $owner->id))->get();
    }

    function record(callable $callback) {
        $callback($this->feature);
        return $this;
    }

    function save(int $count = 1){
        $usage = new FeatureUsage([
            'count' => $count
        ]);

        if($this->owner) {
            $usage->owner()->associate($this->owner);
        }

        if($this->user) {
            $usage->user()->associate($this->user);
        }

        $usage->feature()->associate($this->feature);
        
        $usage->save();
        return $usage;
    }

    function hasReachedLimits(){
        if(!$limit = $this->limit()) return false;
        return $limit >= $this->count();
    }

    public function sum(){
        return $this->history()->sum('count');
    }

    public function count(){
        return $this->history()->count();
    }

    public function limit($withoutPivot = false){
        return $this->getItem('limit', $withoutPivot);
    }
    
    public function resetPeriod($withoutPivot = false) {
        return $this->getItem('reset_period', $withoutPivot);
    }
    
    public function resetInterval($withoutPivot = false) {
        return $this->getItem('reset_interval', $withoutPivot);
    }
    
    function getItem($column, $withoutPivot = false) {
        if(!$withoutPivot && $item = $this->feature->pivot?->{$column}) return $item; 
        return $this->feature->{$column};    
    }

    public function remaining(){
        return $this->limit() - $this->sum();
    }

    function resettablePeriodStart(){
        // return $this->feature->resetPeriod()
    }

    function getCurrentInterval($withoutPivot = false){
        $interval = $this->resetInterval($withoutPivot);
        $period = $this->resetPeriod($withoutPivot);
        
        return CarbonInterval::make($period, $interval);
    }

}