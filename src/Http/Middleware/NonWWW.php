<?php

namespace ReliqArts\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Request;

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
    private const TRUSTED_PROXY_HEADERS = Request::HEADER_X_FORWARDED_FOR |
    Request::HEADER_X_FORWARDED_HOST |
    Request::HEADER_X_FORWARDED_PORT |
    Request::HEADER_X_FORWARDED_PROTO |
    Request::HEADER_X_FORWARDED_PREFIX |
    Request::HEADER_X_FORWARDED_AWS_ELB;

    public function handle($request, Closure $next)
    {
        if (str_starts_with($request->header('host'), 'www.')) {
            $request->setTrustedProxies([$request->getClientIp()], self::TRUSTED_PROXY_HEADERS);
            $request->headers->set('host', parse_url(config('app.url'), PHP_URL_HOST));

            return redirect($request->getRequestUri(), 301);
        }

        return $next($request);
    }
}
