<?php

namespace Intervention\Image\Drivers;

use Intervention\Image\Exceptions\MissingDriverComponentException;
use Intervention\Image\Interfaces\DriverInterface;
use ReflectionClass;

abstract class AbstractDriver implements DriverInterface
{
    public function resolve(object $input): object
    {
        $driver_namespace = (new ReflectionClass($this))->getNamespaceName();
        $class_path = substr(get_class($input), strlen("Intervention\\Image\\"));
        $specialized = $driver_namespace . "\\" . $class_path;

        if (! class_exists($specialized)) {
            throw new MissingDriverComponentException(
                "Class '" . $class_path . "' is not supported by " . $this->id() . " driver."
            );
        }

        return new $specialized($input, $this);
    }
}
