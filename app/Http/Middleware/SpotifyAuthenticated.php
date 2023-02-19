<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SpotifyAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (blank($user->spotify_auth)) {
            return redirect()->route('spotify.authorize');
        }

        return $next($request);
    }
}
