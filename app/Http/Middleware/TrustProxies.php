<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

/**
 * Application-specific proxy trust middleware.
 *
 * Heroku terminates SSL at the router and forwards requests to the dyno
 * using the X-Forwarded-* headers. We trust those proxies so Laravel can
 * correctly detect HTTPS and the client's IP when behind Heroku.
 */
class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     * Use '*' to trust all proxy IPs (Heroku dynos sit behind trusted routers).
     * You can set this via the `TRUSTED_PROXIES` env var if preferred.
     *
     * @var array|string|null
     */
    protected $proxies = null; // use null so we can override via env below

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers = Request::HEADER_X_FORWARDED_ALL;

    public function __construct()
    {
        // If the environment explicitly sets TRUSTED_PROXIES (e.g. '*'), use it.
        $env = env('TRUSTED_PROXIES', null);
        if ($env !== null) {
            // allow a single value or a comma separated list
            if ($env === '*') {
                $this->proxies = '*';
            } else {
                $this->proxies = array_map('trim', explode(',', $env));
            }
        } else {
            // Default to trusting all proxies in Heroku-like envs
            $this->proxies = '*';
        }
    }
}
