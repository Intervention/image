<?php

declare(strict_types=1);

namespace Intervention\Image\Traits;

use Intervention\Image\Exceptions\DirectoryNotFoundException;
use Intervention\Image\Exceptions\FileNotFoundException;
use Intervention\Image\Exceptions\FileNotReadableException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use SplFileInfo;
use Stringable;

trait CanParseFilePath
{
    /**
     * Parse and return existing file path or throw exception.
     *
     * @throws InvalidArgumentException
     * @throws DirectoryNotFoundException
     * @throws FileNotFoundException
     * @throws FileNotReadableException
     */
    protected static function readableFilePathOrFail(mixed $path): string
    {
        if (!is_string($path) && !$path instanceof Stringable) {
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

        if (!is_dir($dirname)) {
            throw new DirectoryNotFoundException('Directory "' . $dirname . '" not found');
        }

        // file must exit
        if (!file_exists($path)) {
            throw new FileNotFoundException('File "' . $basename . '" not found in directory "' . $dirname . '"');
        }

        if (!is_file($path)) {
            throw new FileNotFoundException('"' . $basename . '" is no file in directory "' . $dirname . '"');
        }

        if (!is_readable($dirname)) {
            throw new FileNotReadableException('Directory "' . $dirname . '" is not readable');
        }

        if (!is_readable($path)) {
            throw new FileNotReadableException('File "' . $path . '" is not readable');
        }

        $realpath = realpath($path);

        if ($realpath == false) {
            throw new FileNotReadableException('File "' . $path . '" is not readable');
        }

        return $realpath;
    }

    /**
     * Read real path from given SplFileInfo object or throw exeption.
     *
     * @throws InvalidArgumentException
     * @throws DirectoryNotFoundException
     * @throws FileNotFoundException
     * @throws FileNotReadableException
     */
    protected function filePathFromSplFileInfoOrFail(SplFileInfo $splFileInfo): string
    {
        $path = $splFileInfo->getPathname();
        $dirname = pathinfo($path, PATHINFO_DIRNAME);
        $basename = pathinfo($path, PATHINFO_BASENAME);

        if ($path === '') {
            throw new InvalidArgumentException('Path contained in SplFileInfo must not be an empty string');
        }

        if (!is_dir($dirname)) {
            throw new DirectoryNotFoundException(
                'The directory "' . $dirname . '" contained in SplFileInfo does not exist',
            );
        }

        if (!file_exists($path)) {
            throw new FileNotFoundException(
                'File "' . $basename . '" contained in SplFileInfo was not found in directory "' . $dirname . '"',
            );
        }

        if (!is_file($path)) {
            throw new FileNotFoundException(
                'File "' . $basename . '" contained in SplFileInfo is no file in directory "' . $dirname . '"',
            );
        }

        if (!is_readable($dirname)) {
            throw new FileNotReadableException(
                'Directory "' . $dirname . '" contained in SplFileInfo is not readable',
            );
        }

        if (!is_readable($path)) {
            throw new FileNotReadableException(
                'File "' . $path . '" contained in SplFileInfo is not readable',
            );
        }

        $realpath = $splFileInfo->getRealPath();

        if ($realpath === false) {
            throw new FileNotReadableException('Failed to read file "' . $path . '" from ' . SplFileInfo::class);
        }

        return $realpath;
    }
}
