<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface SpecializableInterface
{
    /**
     * Return the constructor arguments of the specializable object. Keyed by
     * parameter name, used to construct the driver-specialized counterpart.
     *
     * @return array<string, mixed>
     */
    public function specializationArguments(): array;

    /**
     * Set the driver for which the object will be specialized.
     */
    public function setDriver(DriverInterface $driver): self;

    /**
     * Return the driver for which the object is specialized.
     */
    public function driver(): DriverInterface;
}
