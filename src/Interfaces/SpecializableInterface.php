<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface SpecializableInterface
{
    /**
     * Return an array of constructor parameters, which is usually passed from
     * the generic object to the specialized object
     *
     * @return array
     */
    public function specializable(): array;
}
