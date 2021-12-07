<?php

namespace Intervention\Image\Traits;

use Intervention\Image\Exceptions\MissingDriverComponentException;
use Intervention\Image\Exceptions\RuntimeException;
use ReflectionClass;
use ReflectionException;

trait CanResolveDriverClass
{
    /**
     * Resolve given classname according to current driver
     *
     * @param  string $classname
     * @param  array  $arguments
     * @return mixed
     */
    protected function resolveDriverClass(string $classname, ...$arguments)
    {
        $driver_id = $this->getCurrentDriver();
        $classname = sprintf(
            "Intervention\\Image\\Drivers\\%s\\%s",
            ucfirst($driver_id),
            $classname
        );

        try {
            $reflection = new ReflectionClass($classname);
        } catch (ReflectionException $e) {
            throw new MissingDriverComponentException(
                'Class (' . $classname . ') could not be resolved with driver ' . ucfirst($driver_id) . '.'
            );
        }

        return $reflection->newInstanceArgs($arguments);
    }

    protected function getCurrentDriver(): string
    {
        $pattern = '/Intervention\\\Image\\\Drivers\\\(?P<driver>[A-Za-z]+)/';
        preg_match($pattern, get_class($this), $matches);

        if (! array_key_exists('driver', $matches)) {
            throw new RuntimeException('Current driver could not be resolved.');
        }

        return strtolower($matches['driver']);
    }
}
