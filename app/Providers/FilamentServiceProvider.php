<?php

namespace App\Providers;

use Filament\Support\Facades\FilamentView;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        FilamentView::registerRenderHook(
            'panels::body.start',
            fn (): string => '<div dir="rtl">'
        );

        FilamentView::registerRenderHook(
            'panels::body.end',
            fn (): string => '</div>'
        );
    }
}
