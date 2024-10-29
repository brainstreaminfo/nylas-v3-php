<?php

declare(strict_types = 1);

namespace Nylas\Exceptions;

/**
 * Sending Error
 */
class SendingErrorException extends NylasException
{
    protected $code = 422;

    protected $message = 'This is returned during sending. See sending errors';
}
