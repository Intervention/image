<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers;

use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

abstract class DriverSpecialized implements SpecializedInterface
{
    protected DriverInterface $driver;
    protected object $generic;

    public function __construct()
    {
    }

    /**
     * Builds specialized version of the given generic object for a given driver
     *
     * @param object $generic
     * @param DriverInterface $driver
     * @return SpecializedInterface
     */
    public static function buildSpecialized(object $generic, DriverInterface $driver): SpecializedInterface
    {
        $specialized = new static();
        $specialized->generic = $generic;
        $specialized->driver = $driver;

        return $specialized;
    }

    /**
     * Driver for which the object will be specialized
     *
     * @return DriverInterface
     */
    public function driver(): DriverInterface
    {
        return $this->driver;
    }

    /**
     * Underlying generic object which will be specialized for the driver
     *
     * @return object
     */
    public function generic(): object
    {
        return $this->generic;
    }

    /**
     * Magic method to read attributes of underlying generic object
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name): mixed
    {
        return $this->generic->$name;
    }

    /**
     * Magic method to call methods of underlying generic object
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments): mixed
    {
        return $this->generic->$name(...$arguments);
    }
}
