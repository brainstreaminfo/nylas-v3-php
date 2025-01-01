<?php

declare(strict_types=1);

namespace Nylas;

use function ucfirst;
use function class_exists;

use Nylas\Utilities\Options;
use Nylas\Exceptions\NylasException;

/**
 * Nylas Client
 *
 * @property Utilities\Options  Options
 * @property Administration\Abs Administration
 * @property Calendars\Abs      Calendars
 * @property Contacts\Abs       Contacts
 * @property Messages\Abs       Messages
 * @property Threads\Abs        Threads
 * @property Folders\Abs        Folders
 * @property Drafts\Abs         Drafts
 * @property Attachments\Abs    Attachments
 * @property Resources\Abs      Resources
 * @property Events\Abs         Events
 * @property Webhooks\Abs       Webhooks
 */
class Client
{
    /**
     * @var array
     */
    private $objects = [];

    /**
     * Client constructor.
     */
    public function __construct(array $options)
    {
        $this->objects['Options'] = new Options($options);
    }

    /**
     * call nylas apis with __get
     *
     * @param string $name
     *
     * @return object
     */
    public function __get(string $name): object
    {
        return $this->callSubClass($name);
    }

    /**
     * call subclass
     *
     * @param string $name
     *
     * @return object
     */
    private function callSubClass(string $name): object
    {
        $name = ucfirst($name);

        if (!empty($this->objects[$name])) {
            return $this->objects[$name];
        }

        $apiClass = __NAMESPACE__ . '\\' . $name . '\\Abs';

        // check class exists
        if (!class_exists($apiClass)) {
            throw new NylasException(null, "class {$apiClass} not found!");
        }

        return $this->objects[$name] = new $apiClass($this->objects['Options']);
    }

    /**
     * get options instance for setting options
     *
     * @return \Nylas\Utilities\Options
     */
    /*public function Options()
    {
        //return $this->options;
        return $this->objects['Options'];
    }*/
}
