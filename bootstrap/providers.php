<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
    App\Providers\Filament\AuthPanelProvider::class,
    App\Providers\Filament\DeveloperPanelProvider::class,
    App\Providers\Filament\EmployeePanelProvider::class,
    App\Providers\Filament\MainPanelPanelProvider::class,
    App\Providers\Filament\SuperAdminPanelProvider::class,
    Barryvdh\Snappy\ServiceProvider::class,
    Spatie\Permission\PermissionServiceProvider::class,
    Spatie\TranslationLoader\TranslationServiceProvider::class,
];
