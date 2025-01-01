<?php

declare(strict_types=1);

namespace Nylas\Utilities;

use function trim;
use function ucfirst;
use function get_class;
use function class_exists;

use Nylas\Exceptions\NylasException;

/**
 * Nylas Abs
 */
trait Abs
{
    /**
     * @var Options
     */
    private $options;

    /**
     * @var array
     */
    private $objects = [];

    /**
     * Abs constructor.
     *
     * @param Options $options
     */
    public function __construct(Options $options)
    {
        $this->options = $options;
    }

    /**
     * call nylas apis with __get
     *
     * @param string $name
     * @return object|mixed
     */
    public function __get(string $name): object
    {
        return $this->callSubClass($name);
    }

    /**
     * call subclass
     *
     * @param string $name
     * @return object|mixed
     */
    private function callSubClass(string $name): object
    {
        if (!empty($this->objects[$name])) {
            return $this->objects[$name];
        }

        $nmSpace  = trim(get_class($this), 'Abs');
        $subClass = trim($nmSpace, '\\') . '\\' . ucfirst($name);

        // check class exists
        if (!class_exists($subClass)) {
            throw new NylasException(null, "class {$subClass} not found!");
        }

        return $this->objects[$name] = new $subClass($this->options);
    }
}
