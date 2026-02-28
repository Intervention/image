<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use Intervention\Image\File;
use Intervention\Image\Interfaces\ProfileInterface;

class Profile extends File implements ProfileInterface
{
    /**
     * Create color profile instance from given path in file system.
     */
    public static function fromPath(string $path): self
    {
        $pointer = fopen(self::readableFilePathOrFail($path), 'r');

        return new self($pointer);
    }
}
