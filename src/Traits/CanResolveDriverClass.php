<?php

namespace Intervention\Image\Traits;

use ReflectionClass;
use ReflectionException;
use Intervention\Image\Exceptions\RuntimeException;

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
        $classname = sprintf(
            "Intervention\\Image\\Drivers\\%s\\%s",
            ucfirst($this->getCurrentDriver()),
            $classname
        );

        try {
            $reflection = new ReflectionClass($classname);
        } catch (ReflectionException $e) {
            throw new RuntimeException(
                'Class (' . $classname . ') could not be resolved for current driver.'
            );
        }

        return $reflection->newInstanceArgs($arguments);
    }

    protected function getCurrentDriver()
    {
        $pattern = '/Intervention\\\Image\\\Drivers\\\(?P<driver>[A-Za-z]+)/';
        preg_match($pattern, get_class($this), $matches);

        if (! array_key_exists('driver', $matches)) {
            throw new RuntimeException('Current driver could not be resolved.');
        }

        return strtolower($matches['driver']);
    }
}
