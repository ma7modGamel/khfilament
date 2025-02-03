<?php

namespace App\Filament\MainPanal\Pages;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse as Responsable;
use Illuminate\Http\RedirectResponse;
class GlobalLogout implements Responsable
{

    public function toResponse($request): RedirectResponse
    {
        // change this to your desired route
        return redirect()->route('filament.mainPanel.auth.login');
    }
}
