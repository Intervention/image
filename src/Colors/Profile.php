<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use Intervention\Image\File;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\ProfileInterface;

class Profile extends File implements ProfileInterface
{
    /**
     * Create profile object from path in file system
     *
     * @throws RuntimeException
     */
    public static function fromPath(string $path): self
    {
        return new self(fopen($path, 'r'));
    }
}
