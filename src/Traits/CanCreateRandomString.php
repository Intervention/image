<?php

declare(strict_types=1);

namespace Intervention\Image\Traits;

use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\RuntimeException;
use Random\RandomException;

trait CanCreateRandomString
{
    /**
     * Generate ranom string in given length.
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    protected static function randomString(int $length = 32): string
    {
        if ($length <= 0 || $length > 256) {
            throw new InvalidArgumentException('Length must be greater than 0 and less or equal than 256');
        }

        try {
            return substr(bin2hex(random_bytes((int) ceil($length / 2))), 0, $length);
        } catch (RandomException $e) {
            throw new RuntimeException('Failed to create random string', previous: $e);
        }
    }
}
