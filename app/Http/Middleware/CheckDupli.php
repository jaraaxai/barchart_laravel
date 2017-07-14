<?php

namespace App\Http\Middleware;

use Closure;
use App\Quotes;

class CheckDupli
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $q = Quotes::searchOne($request->symbol);
        if ($q) {
            return redirect('/')->withErrors(['This symbol has already been added to the watchlist']);
        }

        return $next($request);
    }
}
