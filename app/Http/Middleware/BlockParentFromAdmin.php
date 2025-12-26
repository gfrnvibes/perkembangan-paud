<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;

class BlockParentFromAdmin
{
    public function handle(Request $request, Closure $next)
    {

        if (auth()->check() && auth()->user()->hasRole('parent')) {
            return redirect('/')
                ->with('error', 'Akses dilarang!');
        }

        if (auth()->check() && auth()->user()->hasRole('parent')) {
            abort(403, 'Parent tidak boleh mengakses admin panel.');
        }


        return $next($request);
    }
}
