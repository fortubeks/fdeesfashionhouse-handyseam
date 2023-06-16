<?php

namespace App\Http\Middleware;

use App\Events\UserActivity;
use Closure;
use Illuminate\Http\Request;

class LogUserActivity
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
        // Call the next middleware
        $response = $next($request);

        // If the user is logged in, log their activity
        if (auth()->check()) { 
            $user = auth()->user();
            $activity = [
                'user_id' => $user->id,
                'route' => $request->path(),
                'method' => $request->method(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
            ];
            event(new UserActivity($activity));
        }

        return $response;
    }
}
