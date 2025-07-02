<?php

namespace SaasPro\Features\Support;

use Illuminate\Database\Eloquent\Model;
use SaasPro\Features\Models\Feature;

class Usage {

    protected Model|null $user = null;
    protected Model|null $owner = null;

    protected array $meta = [];

    function __construct(private Feature|null $feature = null) {
        $this->feature = $feature->load('usageHistory');
    }

    static function for(Feature $feature) {
        return new self($feature);
    }

    function withMeta(array $meta) {
        $this->meta = $meta;
        return $this;
    }

    function forFeature(Feature $feature) {
        $this->feature = $feature;
        return $this;
    }

    function forUser(Model $user){
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

    function save(int | null $count = null){
        $usage = $this->feature->usageHistory()->make([
            'count' => $count
        ])->when($this->owner, fn($usage, $owner) => $usage->owner()->associate($owner))->user()->associate($this->user);
        
        $usage->save();
        // if($this->owner) {
        //     $usage->owner()->associate($this->owner);
        // }

        // $usage->user()->associate($this->user);
        // $usage->save();

        return $usage;
    }

}