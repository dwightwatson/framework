<?php

namespace Illuminate\Routing\Exceptions;

class ExpiredSignatureException extends InvalidSignatureException
{
    /**
     * Create a new exception instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct(403, 'Expired signature.');
    }
}
