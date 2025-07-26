<?php

declare(strict_types=1);

namespace Intervention\Image\Traits;

use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\SpecializableInterface;
use ReflectionClass;

trait CanBeDriverSpecialized
{
    /**
     * The driver with which the instance may be specialized
     */
    protected DriverInterface $driver;

    /**
     * {@inheritdoc}
     *
     * @see SpecializableInterface::specializable()
     */
    public function specializable(): array
    {
        $specializable = [];

        $reflectionClass = new ReflectionClass($this::class);
        if ($constructor = $reflectionClass->getConstructor()) {
            foreach ($constructor->getParameters() as $parameter) {
                $specializable[$parameter->getName()] = $this->{$parameter->getName()};
            }
        }

        return $specializable;
    }

    /**
     * {@inheritdoc}
     *
     * @see SpecializableInterface::driver()
     */
    public function driver(): DriverInterface
    {
        return $this->driver;
    }

    /**
     * {@inheritdoc}
     *
     * @see SpecializableInterface::setDriver()
     */
    public function setDriver(DriverInterface $driver): SpecializableInterface
    {
        if (!$this->belongsToDriver($driver)) {
            throw new DriverException(
                "Class '" . $this::class . "' can not be used with " . $driver->id() . " driver."
            );
        }

        $this->driver = $driver;

        return $this;
    }

    /**
     * Determine if the current object belongs to the given driver's namespace
     */
    protected function belongsToDriver(object $driver): bool
    {
        $namespace = function (object $object): string {
            return (new ReflectionClass($object))->getNamespaceName();
        };

        return str_starts_with($namespace($this), $namespace($driver));
    }
}
