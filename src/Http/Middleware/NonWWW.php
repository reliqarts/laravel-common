<?php

namespace ReliqArts\Http\Middleware;

use Closure;

/**
 * Redirects any www requests to non-www counterparts.
 *
 * @param mixed   $request the request object
 * @param Closure $next    the next closure
 *
 * @return mixed redirects to the secure counterpart of the requested uri
 */
class NonWWW
{
    public function handle($request, Closure $next)
    {
        if (str_starts_with($request->header('host'), 'www.')) {
            $request->setTrustedProxies([$request->getClientIp()], config('trustedproxy.headers'));
            $request->headers->set('host', parse_url(config('app.url'), PHP_URL_HOST));

            return redirect($request->getRequestUri(), 301);
        }

        return $next($request);
    }
}
