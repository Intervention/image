<?php

namespace Intervention\Image\Drivers;

use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\ModifierInterface;

abstract class DriverSpecializedModifier implements ModifierInterface
{
    public function __construct(
        protected ModifierInterface $modifier,
        protected DriverInterface $driver
    ) {
    }

    public function driver(): DriverInterface
    {
        return $this->driver;
    }

    /**
     * Magic method to read attributes of underlying modifier
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name): mixed
    {
        return $this->modifier->$name;
    }

    /**
     * Magic method to call methods of underlying modifier
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments): mixed
    {
        return $this->modifier->$name(...$arguments);
    }
}
