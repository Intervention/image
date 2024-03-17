<?php

declare(strict_types=1);

namespace Intervention\Image\Traits;

use ReflectionClass;

trait CanBeDriverSpecialized
{
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
}
