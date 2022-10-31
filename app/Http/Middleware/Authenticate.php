<?php

namespace App\Http\Middleware;

use App\Models\AccessToken;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Laravel\Sanctum\PersonalAccessToken;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('home');
        }
    }

    public function handle($request, Closure $next, ...$guards)
    {
        $token = request()->bearerToken();
        if (empty($token)) {
            $this->unauthenticated($request, $guards);
        }
        $token = \Cache::remember('access_token', config('cache.access_token_lifetime'), function () use ($token) {
            return AccessToken::where(['token' => AccessToken::generateHash($token)])->first();
        });
        if (is_null($token)) {
            $this->unauthenticated($request, $guards);
        }

        return $next($request);
    }
}
