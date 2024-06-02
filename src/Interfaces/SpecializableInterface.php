<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\DriverException;

interface SpecializableInterface
{
    /**
     * Return an array of constructor parameters, which is usually passed from
     * the generic object to the specialized object
     *
     * @return array<string, mixed>
     */
    public function specializable(): array;

    /**
     * Set the driver for which the object is specialized
     *
     * @param DriverInterface $driver
     * @throws DriverException
     * @return SpecializableInterface
     */
    public function setDriver(DriverInterface $driver): self;

    /**
     * Return the driver for which the object was specialized
     *
     * @return DriverInterface
     */
    public function driver(): DriverInterface;
}
