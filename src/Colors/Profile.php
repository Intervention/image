<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use Intervention\Image\Exceptions\FileNotReadableException;
use Intervention\Image\File;
use Intervention\Image\Interfaces\ProfileInterface;

class Profile extends File implements ProfileInterface
{
    public static function fromPath(string $path): self
    {
        $pointer = fopen(self::parseFilePathOrFail($path), 'r');

        if ($pointer === false) {
            throw new FileNotReadableException('Failed to open profile from path "' . $path . '"');
        }

        return new self($pointer);
    }
}
