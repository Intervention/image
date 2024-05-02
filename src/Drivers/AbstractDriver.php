<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers;

use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\AnalyzerInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SpecializableInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use ReflectionClass;

abstract class AbstractDriver implements DriverInterface
{
    /**
     * @throws DriverException
     */
    public function __construct()
    {
        $this->checkHealth();
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::specialize()
     */
    public function specialize(
        ModifierInterface|AnalyzerInterface|EncoderInterface|DecoderInterface $object
    ): ModifierInterface|AnalyzerInterface|EncoderInterface|DecoderInterface {
        // return object directly if no specializing is possible
        if (!($object instanceof SpecializableInterface)) {
            return $object;
        }

        // return directly if object is already specialized
        if ($object instanceof SpecializedInterface) {
            return $object;
        }

        // resolve classname for specializable object
        $driver_namespace = (new ReflectionClass($this))->getNamespaceName();
        $object_path = substr($object::class, strlen("Intervention\\Image\\"));
        $specialized_classname = $driver_namespace . "\\" . $object_path;

        if (!class_exists($specialized_classname)) {
            throw new NotSupportedException(
                "Class '" . $object_path . "' is not supported by " . $this->id() . " driver."
            );
        }

        // create driver specialized object with specializable properties of generic object
        $specialized = (new $specialized_classname(...$object->specializable()));

        // attach driver
        return $specialized->setDriver($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::specializeMultiple()
     */
    public function specializeMultiple(array $objects): array
    {
        return array_map(function ($object) {
            return $this->specialize(
                match (true) {
                    is_string($object) => new $object(),
                    is_object($object) => $object,
                }
            );
        }, $objects);
    }
}
