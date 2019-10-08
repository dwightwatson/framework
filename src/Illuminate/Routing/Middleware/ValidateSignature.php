<?php

namespace Illuminate\Routing\Middleware;

use Closure;
use Illuminate\Routing\Exceptions\ExpiredSignatureException;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Support\Carbon;

class ValidateSignature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Routing\Exceptions\InvalidSignatureException
     */
    public function handle($request, Closure $next)
    {
        if (! $request->hasValidSignature()) {
            throw $this->hasExpiredSignature($request)
                ? new ExpiredSignatureException
                : new InvalidSignatureException;
        }

        return $next($request);
    }

    /**
     * Determine if the signature has expired.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function hasExpiredSignature($request)
    {
        $expires = $request->query('expires');

        return $expires && Carbon::now()->getTimestamp() > $expires;
    }
}
