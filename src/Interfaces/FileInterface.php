<?php

namespace Intervention\Image\Interfaces;

interface FileInterface
{
    /**
     * Save data in given path in file system
     *
     * @param  string $filepath
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
