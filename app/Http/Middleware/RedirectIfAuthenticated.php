<?php

namespace App\Http\Middleware;

use App\Core\Error\ErrorManager;
use App\Core\Response\ResponseTrait;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect(RouteServiceProvider::HOME);
            } else {
                if($request->bearerToken()){
                    // Если есть bearer токен, но что то с ним не то
                    return $this->errorResponse(
                        ErrorManager::buildError(VALIDATION_UNAUTHORIZED),
                        Response::HTTP_UNAUTHORIZED
                    );
                }
            }
        }

        return $next($request);
    }
}
