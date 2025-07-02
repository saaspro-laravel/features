<?php

namespace Utyemma\SaasPro\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class HasFeature {

    function __construct(private string|array $feature) {

    }

    function can() {
        
    }

}