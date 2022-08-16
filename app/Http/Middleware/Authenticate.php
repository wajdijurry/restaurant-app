<?php

namespace App\Http\Middleware;

use App\Enums\UserTypeEnum;
use App\Models\User;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    public function authenticate($request, array $guards)
    {
        // Demo purposes only
        Auth::login(User::query()->where('type', UserTypeEnum::USER_TYPE_CUSTOMER)->first());

        parent::authenticate($request, $guards);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
