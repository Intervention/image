<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\RuntimeException;

interface FileInterface
{
    /**
     * Save data in given path in file system
     *
     * @param string $filepath
     * @throws RuntimeException
     * @return void
     */
    public function save(string $filepath): void;

    /**
     * Create file pointer from encoded data
     *
     * @return resource
     */
    public function toFilePointer();

    /**
     * Return size in bytes
     *
     * @return int
     */
    public function size(): int;

    /**
     * Turn encoded data into string
     *
     * @return string
     */
    public function toString(): string;

    /**
     * Cast encoded data into string
     *
     * @return string
     */
    public function __toString(): string;
}
