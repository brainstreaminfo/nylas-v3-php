<?php

declare(strict_types = 1);

namespace Nylas\Exceptions;

/**
 * Server Error
 */
class ServerErrorException extends NylasException
{
    protected $code = 500;

    protected $message = 'An error occurred in the Nylas server. If this persists, please see our status page or contact support.';
}
