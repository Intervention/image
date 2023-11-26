<?php

namespace Intervention\Image\Drivers;

use Intervention\Image\Analyzers\AbstractAnalyzer;
use Intervention\Image\Encoders\AbstractEncoder;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Modifiers\AbstractModifier;
use ReflectionClass;

abstract class AbstractDriver implements DriverInterface
{
    public function resolve(object $input): object
    {
        if ($this->isExternal($input)) {
            return $input;
        }

        $driver_namespace = (new ReflectionClass($this))->getNamespaceName();
        $class_path = substr(get_class($input), strlen("Intervention\\Image\\"));
        $specialized = $driver_namespace . "\\" . $class_path;

        if (! class_exists($specialized)) {
            throw new NotSupportedException(
                "Class '" . $class_path . "' is not supported by " . $this->id() . " driver."
            );
        }

        return new $specialized($input, $this);
    }

    private function isExternal(object $input): bool
    {
        if ($input instanceof AbstractModifier) {
            return false;
        }

        if ($input instanceof AbstractAnalyzer) {
            return false;
        }

        if ($input instanceof AbstractEncoder) {
            return false;
        }

        return true;
    }
}
