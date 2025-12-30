<?php

declare(strict_types=1);

namespace Intervention\Image\Traits;

use Intervention\Image\Exceptions\DirectoryNotFoundException;
use Intervention\Image\Exceptions\FileNotFoundException;
use Intervention\Image\Exceptions\FileNotReadableException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Stringable;

trait CanParseFilePath
{
    /**
     * Parse and return existing file path or throw exception
     */
    protected static function readableFilePathOrFail(mixed $path): string
    {
        if (!is_string($path) && !($path instanceof Stringable)) {
            throw new InvalidArgumentException('Path must be either of type string or instance of Stringable');
        }

        $path = (string) $path;

        if ($path === '') {
            throw new InvalidArgumentException('Path must not be an empty string');
        }

        if (strlen($path) > PHP_MAXPATHLEN) {
            throw new InvalidArgumentException(
                "Path is longer than the configured max. value of " . PHP_MAXPATHLEN
            );
        }

        // get info on path
        $dirname = pathinfo($path, PATHINFO_DIRNAME);
        $basename = pathinfo($path, PATHINFO_BASENAME);

        // directory must exist
        if (!@is_dir($dirname)) {
            throw new DirectoryNotFoundException('Directory "' . $dirname . '" not found');
        }

        // file must exit
        if (!@file_exists($path)) {
            throw new FileNotFoundException('File "' . $basename . '" not found in directory "' . $dirname . '"');
        }

        if (!@is_file($path)) {
            throw new FileNotFoundException('Target "' . $basename . '" is no file in directory "' . $dirname . '"');
        }

        if (!@is_readable($dirname)) {
            throw new FileNotReadableException('Directory "' . $dirname . '" is not readable');
        }

        if (!@is_readable($path)) {
            throw new FileNotReadableException('File "' . $path . '" is not readable');
        }

        return realpath($path);
    }
}
