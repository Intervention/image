<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface ProfileInterface
{
    /**
     * Cast color profile object to string
     *
     * @return string
     */
    public function __toString(): string;
}
