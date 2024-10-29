<?php

declare(strict_types = 1);

namespace Nylas\Exceptions;

/**
 * Gone
 */
class GoneException extends NylasException
{
    protected $code = 410;

    protected $message = 'The requested resource has been removed from our servers.';
}
