<?php

declare(strict_types = 1);

namespace Nylas\Exceptions;

/**
 * Method Not Allowed
 */
class MethodNotAllowedException extends NylasException
{
    protected $code = 405;

    protected $message = 'You tried to access a resource with an invalid method.';
}
