<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Form;
use Illuminate\Http\RedirectResponse;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Register as AuthManager;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;

class Register extends AuthManager
{
  

    public function register(): ?RegistrationResponse
    {
        $response = parent::register();

        if ($response !== null) {
           

            // Return a RedirectResponse to the login route
            return new class(route('panel.register')) implements RegistrationResponse {
                private $url;

                public function __construct($url)
                {
                    $this->url = $url;
                }

                public function toResponse($request)
                {
                    return redirect()->to($this->url);
                }
            };
        }

        return $response;
    }
}
