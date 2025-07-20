<?php

namespace SaasPro\Features;

use Filament\Panel;


class PluginProvider {

    public function filament(Panel $panel){
        return $panel
            ->discoverPages(in: __DIR__.'/Filament/Pages', for: 'SaasPro\\Features\\Filament\\Pages')
            ->discoverResources(in: __DIR__."/Filament/Resources", for: 'SaasPro\\Features\\Filament\\Resources')
            ->discoverWidgets(in:  __DIR__.'/Filament/Widgets', for: 'SaasPro\\Features\\Filament\\Widgets');
    }

}