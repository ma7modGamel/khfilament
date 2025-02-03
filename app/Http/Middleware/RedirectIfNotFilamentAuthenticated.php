<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotFilamentAuthenticated extends Middleware
{

    protected function redirectTo($request): ?string
    {
        return route('filament.auth.auth.login');
    }
}
