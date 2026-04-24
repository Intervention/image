<?php

declare(strict_types=1);

namespace Intervention\Image\Traits;

use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\DriverInterface;

trait CanResolveDriver
{
    /**
     * Resolve given string or driver to a driver instance with given options.
     *
     * @throws InvalidArgumentException
     */
    protected static function resolveDriver(string|DriverInterface $driver, mixed ...$options): DriverInterface
    {
        if (is_string($driver) && !class_exists($driver)) {
            throw new InvalidArgumentException(
                'Argument $driver must be existing class name'
            );
        }

        if (is_string($driver) && !is_subclass_of($driver, DriverInterface::class)) {
            throw new InvalidArgumentException(
                'Argument $driver must be an implementation of ' . DriverInterface::class,
            );
        }

        $driver = is_string($driver) ? new $driver() : $driver;
        $driver->config()->setOptions(...$options);

        return $driver;
    }
}
