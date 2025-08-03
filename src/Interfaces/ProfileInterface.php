<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Stringable;

interface ProfileInterface extends Stringable
{
    /**
     * Create profile object from path in file system
     */
    public static function fromPath(string $path): self;

    /**
     * Transform object to string
     */
    public function toString(): string;
}
