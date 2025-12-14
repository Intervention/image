<?php

declare(strict_types=1);

namespace Intervention\Image\Traits;

use http\Exception\InvalidArgumentException;
use Intervention\Image\Exceptions\DirectoryNotFoundException;
use Intervention\Image\Exceptions\FileNotFoundException;
use Intervention\Image\Exceptions\FileNotReadableException;
use Stringable;

trait CanParseFilePath
{
    /**
     * Parse and return existing file path or throw exception
     *
     * @throws InvalidArgumentException
     */
    protected static function parseFilePathOrFail(mixed $path): string
    {
        if (!is_string($path) && !($path instanceof Stringable)) {
            throw new InvalidArgumentException('Path must be either of type string or instance of Stringable');
        }

        $path = (string) $path;

        if ($path === '') {
            // NEWEX
            throw new InvalidArgumentException('Path must not be an empty string');
        }

        if (strlen($path) > PHP_MAXPATHLEN) {
            // NEWEX
            throw new InvalidArgumentException(
                "Path is longer than the configured max. value of " . PHP_MAXPATHLEN
            );
        }

        // get info on path
        $dirname = pathinfo($path, PATHINFO_DIRNAME);
        $basename = pathinfo($path, PATHINFO_BASENAME);

        if (!is_dir($dirname)) {
            // NEWEX
            throw new DirectoryNotFoundException('Directory "' . $dirname . '" not found');
        }

        if (!@is_file($path)) {
            // NEWEX
            throw new FileNotFoundException('File "' . $basename . '" not found in directory "' . $dirname . '"');
        }

        if (!is_readable($dirname)) {
            // NEWEX
            throw new FileNotReadableException('Directory "' . $dirname . '" is not readable');
        }

        if (!is_readable($path)) {
            // NEWEX
            throw new FileNotReadableException('File "' . $path . '" is not readable');
        }

        return $path;
    }
}
