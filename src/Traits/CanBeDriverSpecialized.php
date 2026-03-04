<?php

declare(strict_types=1);

namespace Intervention\Image\Traits;

use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\SpecializableInterface;
use ReflectionClass;

trait CanBeDriverSpecialized
{
    /**
     * Cache for constructor parameter names keyed by class name.
     *
     * @var array<string, array<int, string>>
     */
    private static array $parameterCache = [];

    /**
     * The driver with which the instance will be specialized.
     */
    protected DriverInterface $driver;

    /**
     * {@inheritdoc}
     *
     * @see SpecializableInterface::specializationArguments()
     */
    public function specializationArguments(): array
    {
        $class = $this::class;

        if (!isset(self::$parameterCache[$class])) {
            $names = [];
            $reflectionClass = new ReflectionClass($class);
            if ($constructor = $reflectionClass->getConstructor()) {
                foreach ($constructor->getParameters() as $parameter) {
                    $names[] = $parameter->getName();
                }
            }
            self::$parameterCache[$class] = $names;
        }

        $specializable = [];
        foreach (self::$parameterCache[$class] as $name) {
            $specializable[$name] = $this->{$name};
        }

        return $specializable;
    }

    /**
     * {@inheritdoc}
     *
     * @see SpecializableInterface::driver()
     *
     * @throws StateException
     */
    public function driver(): DriverInterface
    {
        if (!isset($this->driver)) {
            throw new StateException(
                'Use setDriver() on ' . $this::class . ' to provide an applicable ' . DriverInterface::class,
            );
        }

        return $this->driver;
    }

    /**
     * {@inheritdoc}
     *
     * @see SpecializableInterface::setDriver()
     *
     * @throws NotSupportedException
     */
    public function setDriver(DriverInterface $driver): SpecializableInterface
    {
        if (!$this->belongsToDriver($driver)) {
            throw new NotSupportedException(
                "Class '" . $this::class . "' can not be used with " . $driver->id() . " driver"
            );
        }

        $this->driver = $driver;

        return $this;
    }

    /**
     * Determine if the current object belongs to the given driver's namespace.
     */
    protected function belongsToDriver(object $driver): bool
    {
        return str_starts_with(
            $this::class,
            substr($driver::class, 0, (int) strrpos($driver::class, '\\')), // driver namespace
        );
    }
}
