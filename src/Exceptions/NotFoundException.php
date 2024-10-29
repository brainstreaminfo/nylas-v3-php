<?php

declare(strict_types = 1);

namespace Nylas\Exceptions;

/**
 * Not Found
 */
class NotFoundException extends NylasException
{
    protected $code = 404;

    protected $message = "The requested item doesn't exist.";
}
