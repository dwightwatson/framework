<?php

namespace Illuminate\Routing\Middleware;

use Closure;
use Illuminate\Routing\Exceptions\ExpiredSignatureException;
use Illuminate\Routing\Exceptions\InvalidSignatureException;

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
        if ($request->hasExpiredSignature()) {
            throw new ExpiredSignatureException;
        }

        if (! $request->hasValidSignature()) {
            throw new InvalidSignatureException;
        }

        return $next($request);
    }
}
