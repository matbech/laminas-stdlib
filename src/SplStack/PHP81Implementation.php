<?php

declare(strict_types=1);

namespace Laminas\Stdlib\SplStack;

use SplStack;
use UnexpectedValueException;

use function serialize;
use function unserialize;

/**
 * Serializable version of SplStack
 */
class PHP81Implementation extends SplStack
{
    /**
     * Serialize to an array representing the stack
     *
     * @return array
     */
    public function toArray()
    {
        $array = [];
        foreach ($this as $item) {
            $array[] = $item;
        }
        return $array;
    }

    /**
     * Serialize
     */
    public function serialize(): string
    {
        return serialize($this->toArray());
    }

    /**
     * Magic method used for serializing of an instance.
     */
    public function __serialize(): array
    {
        return $this->toArray();
    }

    /**
     * Unserialize
     */
    public function unserialize(string $data): void
    {
        $toUnserialize = unserialize($data);
        if (! is_array($toUnserialize)) {
            throw new UnexpectedValueException(sprintf(
                'Unable to deserialize to Laminas\Stdlib\SplStack; expected array, received %s',
                is_object($toUnserialize) ? get_class($toUnserialize) : gettype($toUnserialize)
            ));
        }
        $this->__unserialize($toUnserialize);
    }

   /**
    * Magic method used to rebuild an instance.
    */
    public function __unserialize(array $data): void
    {
        /** @psalm-suppress MixedAssignment */
        foreach ($data as $item) {
            $this->unshift($item);
        }
    }
}
