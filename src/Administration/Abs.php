<?php

declare(strict_types = 1);

namespace Nylas\Administration;

use Nylas\Utilities\Abs as AbsTrait;

/**
 * @property Application  Application
 * @property Connectors  Connectors
 * @property Grants  Grants
 * @property Authentication Authentication
 * @property ConnectorsCredentials ConnectorsCredentials
 */
class Abs
{
    use AbsTrait;
}
