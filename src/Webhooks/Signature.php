<?php

declare(strict_types=1);

namespace Nylas\Webhooks;

use function header;
use function hash_hmac;
use function json_decode;

use function json_last_error;
use function file_get_contents;

use Throwable;
use Nylas\Utilities\Options;
use Nylas\Exceptions\NylasException;

/**
 * Nylas Webhooks Signature
 */
class Signature
{
    /**
     * Manage constructor.
     *
     * @param Options $options
     */
    private $options;

    public function __construct(Options $options)
    {
        $this->options = $options;
    }

    /**
     * echo challenge to validate webhook (for fpm mode)
     *
     * TIPS: you'd better use the output method from your framework.
     *
     * @return void
     */
    public function echoChallenge(): void
    {
        $challenge = $_GET['challenge'] ?? null;

        if (empty($challenge)) {
            return;
        }

        header('Content-Type: text/html; charset=utf-8', true, 200);

        exit($challenge);
    }

    /**
     * get notification & parse it (for fpm mode)
     *
     * @return array
     */
    public function getNotification(): array
    {
        $data = file_get_contents('php://input');
        $code = $_SERVER['HTTP_X_NYLAS_SIGNATURE'] ?? '';
        $vrif = $this->xSignatureVerification($code, $data);

        // check if valid
        if ($vrif === false) {
            throw new NylasException(null, 'not a valid nylas request');
        }

        // parse notification data
        return $this->parseNotification($data);
    }

    /**
     * webhook X-Nylas-Signature header verification (for other mode)
     * @see https://docs.nylas.com/reference#receiving-notifications
     *
     * @param string $code
     * @param string $data
     * @return bool
     */
    public function xSignatureVerification(string $code, string $data): bool
    {
        $conf = $this->options->getApiKey();

        $hash = hash_hmac('sha256', $data, $conf);

        return $code === $hash;
    }

    /**
     * parse notification data
     *
     * @param string $data
     * @return array|string
     */
    public function parseNotification(string $data)
    {
        try {
            $data = json_decode($data, true, 512);
        } catch (Throwable $e) {
            // when not close the decode error
            if ($this->options->getAllOptions()['debug']) {
                $msg = 'Unable to parse response body into JSON: ';

                throw new NylasException(null, $msg . json_last_error());
            }
        }

        // check deltas
        if (!isset($data['deltas'])) {
            throw new NylasException(null, 'invalid data');
        }

        return $data['deltas'];
    }
}
