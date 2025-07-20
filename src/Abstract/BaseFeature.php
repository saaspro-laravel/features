<?php

namespace SaasPro\Features\Abstracts;

use Exception;
use Illuminate\Database\Eloquent\Model;
use SaasPro\Features\Contracts\FeatureContract;
use SaasPro\Features\Models\Feature;
use SaasPro\Features\Support\FeatureState;

abstract class BaseFeature implements FeatureContract {

    protected Feature | null $feature = null;
    protected array $context = [];

    function __get($name) {
        if($value = $this->context[$name]) return $value;
    }

    function response($status, string $message = ''): FeatureState {
        return FeatureState::dispatch($status)
                    ->withFeature($this->feature)
                    ->withMessage($message);
    }

    public function withContext(array $context = []) {
        $this->context = $context;

        array_map(function($context, $index){
            if(property_exists($this, $index)) {
                $this->{$index} = $context;
            }
        }, $context);

        return $this;
    }
    
    public function feature(){
        if(!$this->feature = Feature::whereFeatureClass($className = static::class)->first()){
             throw new Exception("The requested feature {$className} does not exist.");
        }

        return $this->feature;
    }

    public function validate(array $context = []): FeatureState {  
        $this->withContext($context);
        return $this->validator($this->feature());     
    }

    function usage(){
        return $this->feature->usage();
    }

    public function use(){
        $this->record($this->usage());
        $this->afterUsage();
    }

    public function afterUsage(): void { 

    }    

}