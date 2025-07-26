<?php

namespace SaasPro\Features\Support;

use Illuminate\Database\Eloquent\Model;
use SaasPro\Features\Contracts\InteractsWithFeatures;
use SaasPro\Features\Models\Feature;
use SaasPro\Features\Models\FeatureUsage;

class Usage {

    protected ?Model $owner = null;
    protected array $meta = [];

    function __construct(private ?Feature $feature = null, protected ?InteractsWithFeatures $user = null) {
        $this->feature = $feature->load('usageHistory');
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
        return $this->feature->usageHistory()
                ->when($this->user, fn($query, $user) => $query->whereRelation('user', 'id', $user->id))->when($this->owner, fn($query, $owner) => $query->whereRelation('owner', 'id', $owner->id))->get();
    }

    function record(callable $callback) {
        $callback($this->feature);
        return $this;
    }

    function save(int $count = 1){
        $usage = new FeatureUsage([
                            'count' => $count
        ]);

        $usage->when($this->owner, function($usage, $owner) { 
            $usage->owner()->associate($owner);
        })->when($this->user, function($usage, $user){
            $usage->user()->associate($user);
        });

        $usage->feature()->associate($this->feature); 
        $usage->save();
        return $usage;
    }

}