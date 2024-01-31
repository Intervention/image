<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers;

use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\AnalyzerInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SpecializableInterface;
use ReflectionClass;

abstract class AbstractDriver implements DriverInterface
{
    public function __construct()
    {
        $this->checkHealth();
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::specialize()
     */
    public function specialize(object $object): ModifierInterface|AnalyzerInterface|EncoderInterface|DecoderInterface
    {
        if (!($object instanceof SpecializableInterface)) {
            return $object;
        }

        $driver_namespace = (new ReflectionClass($this))->getNamespaceName();
        $class_path = substr($object::class, strlen("Intervention\\Image\\"));
        $classname = $driver_namespace . "\\" . $class_path;

        if (!class_exists($classname)) {
            throw new NotSupportedException(
                "Class '" . $class_path . "' is not supported by " . $this->id() . " driver."
            );
        }

        return forward_static_call([
            $classname,
            'buildSpecialized'
        ], $object, $this);
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::specializeMultiple()
     */
    public function specializeMultiple(array $specializables): array
    {
        return array_map(function ($specializable) {
            return $this->specialize(
                match (true) {
                    is_string($specializable) => new $specializable(),
                    is_object($specializable) => $specializable,
                    default => throw new RuntimeException(
                        'Specializable item must be either a class name or an object.'
                    )
                }
            );
        }, $specializables);
    }
}
