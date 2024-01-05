<?php

namespace Intervention\Image\Drivers;

use Intervention\Image\Exceptions\NotSupportedException;
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
     * Return a specialized version for the current driver of the given object
     *
     * @param object $input
     * @return ModifierInterface|AnalyzerInterface|EncoderInterface|DecoderInterface
     * @throws NotSupportedException
     */
    public function specialize(object $input): ModifierInterface|AnalyzerInterface|EncoderInterface|DecoderInterface
    {
        if (!($input instanceof SpecializableInterface)) {
            return $input;
        }

        $driver_namespace = (new ReflectionClass($this))->getNamespaceName();
        $class_path = substr(get_class($input), strlen("Intervention\\Image\\"));
        $classname = $driver_namespace . "\\" . $class_path;

        if (!class_exists($classname)) {
            throw new NotSupportedException(
                "Class '" . $class_path . "' is not supported by " . $this->id() . " driver."
            );
        }

        return forward_static_call([
            $classname,
            'buildSpecialized'
        ], $input, $this);
    }
}
