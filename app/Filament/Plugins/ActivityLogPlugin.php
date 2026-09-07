<?php

namespace App\Filament\Plugins;

use App\Filament\Admin\Resources\ActivityResource;
use Filament\Contracts\Plugin;
use Filament\Panel;

class ActivityLogPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-logger';
    }

    public function register(Panel $panel): void
    {
        // Register our custom ActivityResource instead of the vendor one
        $panel->resources([
            ActivityResource::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }
}
