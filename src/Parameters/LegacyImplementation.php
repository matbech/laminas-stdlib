<?php

declare(strict_types=1);

namespace Laminas\Stdlib\Parameters;

use ArrayObject;
use Laminas\Stdlib\ParametersInterface;

use function http_build_query;
use function parse_str;

class LegacyImplementation extends ArrayObject implements ParametersInterface
{
    /**
     * Constructor
     *
     * Enforces that we have an array, and enforces parameter access to array
     * elements.
     *
     * @param  array $values
     */
    public function __construct(?array $values = null)
    {
        if (null === $values) {
            $values = [];
        }
        parent::__construct($values, ArrayObject::ARRAY_AS_PROPS);
    }

    /**
     * Populate from native PHP array
     *
     * @param  array $values
     * @return void
     */
    public function fromArray(array $values)
    {
        $this->exchangeArray($values);
    }

    /**
     * Populate from query string
     *
     * @param  string $string
     * @return void
     */
    public function fromString($string)
    {
        $array = [];
        parse_str($string, $array);
        $this->fromArray($array);
    }

    /**
     * Serialize to native PHP array
     *
     * @return array
     */
    public function toArray()
    {
        return $this->getArrayCopy();
    }

    /**
     * Serialize to query string
     *
     * @return string
     */
    public function toString()
    {
        return http_build_query($this->toArray());
    }

    /**
     * Retrieve by key
     *
     * Returns null if the key does not exist.
     *
     * @param  string $name
     * @return mixed
     */
    public function offsetGet($name)
    {
        if ($this->offsetExists($name)) {
            return parent::offsetGet($name);
        }

        return null;
    }

    /**
     * @param string $name
     * @param mixed $default optional default value
     * @return mixed
     */
    public function get($name, $default = null)
    {
        if ($this->offsetExists($name)) {
            return parent::offsetGet($name);
        }
        return $default;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return static
     */
    public function set($name, $value)
    {
        $this[$name] = $value;
        return $this;
    }
}
