<?php

declare(strict_types = 1);

namespace Nylas\Exceptions;

/**
 * Teapot
 */
class TeapotException extends NylasException
{
    protected $code = 418;

    protected $message = "I'm a teapot";
}
