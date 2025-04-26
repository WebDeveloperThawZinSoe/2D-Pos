<?php

namespace App\Providers;

use Filament\Support\Facades\FilamentIcon;
use Illuminate\Support\ServiceProvider;

class FilamentIconServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        FilamentIcon::register([
            'heroicon-o-home' => 'fa-solid fa-house', // FontAwesome Home icon
            'heroicon-o-user' => 'fa-solid fa-user', // FontAwesome User icon
            'heroicon-o-cog'  => 'fa-solid fa-gear', // FontAwesome Settings icon
            'heroicon-o-rectangle-stack' => 'fa-solid fa-layer-group', // FontAwesome
            'heroicon-o-user' => 'fa-solid fa-user', // Example replacement
        ]);
    }
}
