<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface SpecializedInterface
{
    /**
     * Return the driver for which the object was specialized
     *
     * @return DriverInterface
     */
    public function driver(): DriverInterface;

    /**
     * Set the driver for which the object is specialized
     *
     * @param DriverInterface $driver
     * @return SpecializableInterface
     */
    public function setDriver(DriverInterface $driver): self;
}
