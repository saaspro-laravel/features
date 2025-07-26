<?php

namespace SaasPro\Features\Abstracts;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Facade;
use SaasPro\Features\Contracts\FeatureContract;
use SaasPro\Features\Contracts\InteractsWithFeatures;
use SaasPro\Features\Models\Feature;
use SaasPro\Features\Support\FeatureState;
use SaasPro\Features\Support\Usage;

abstract class BaseFeature implements  FeatureContract {

    protected Feature | null $feature = null;
    protected array $context = [];

    function __construct(protected InteractsWithFeatures | null $user = null){
        if($user) $this->forUser($user ?? Auth::user());
        $this->feature();
    }

    public static function new(){
        return new static;
    }

    function __get($name) {
        if($value = $this->context[$name]) return $value;
    }

    function response($status, string $message = ''): FeatureState {
        return FeatureState::dispatch($status)
                    ->withFeature($this->feature)
                    ->withMessage($message);
    }

    public function callForUser(InteractsWithFeatures $user): static{
        $this->user = $user;
        return $this;
    }

    public function callValidate(array $context = []): FeatureState {  
        $this->withContext($context);
        $state = FeatureState::withFeature($this->feature())->withUser($this->user)->withUsage($this->usage());
        return $this->validator($state);     
    }
    
    public function callCan(array $context = []){
        return $this->validate($context)->can();
    }

    public function callUse(){
        $this->record($this->usage());
        $this->afterUsage();
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

    public function usage(){
        return Usage::for($this->feature, $this->user);
    }

    public function record(Usage $usage): void{
        $usage->save();
    }   

    public function afterUsage(): void { }  

    public function __call($method, $args){
        if(method_exists($this::class, $name = "call".str($method)->headline())) {
            return $this->{$name}(...$args);
        }
    }

    public static function __callStatic($method, $args){
        if(method_exists(static::class, $name = "call".str($method)->headline())) {
            return (new static)->$name(...$args);
        }
    }

}