<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Page;
use Filament\Auth\Pages\Login as LoginCustom;

class Login extends LoginCustom
{
    protected string $view = 'filament.pages.auth.login';

    protected function redirectTo(): string
    {
        request()->session()->forget('url.intended');

        $user = auth()->user();

        if ($user->hasRole('parent')) {
            return '/';
        }

        return route('filament.admin.pages.dashboard');
    }

}


