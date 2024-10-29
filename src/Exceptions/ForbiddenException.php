<?php

declare(strict_types = 1);

namespace Nylas\Exceptions;

/**
 * Forbidden
 */
class ForbiddenException extends NylasException
{
    protected $code = 403;

    protected $message = 'Includes authentication errors, blocked developer applications, and cancelled accounts.';
}
