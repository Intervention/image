<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\RuntimeException;
use Stringable;

interface FileInterface extends Stringable
{
    /**
     * Create file object from path in file system
     */
    public static function fromPath(string $path): self;

    /**
     * Save data in given path in file system
     *
     * @throws RuntimeException
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
     */
    public function size(): int;

    /**
     * Transform file object into string
     */
    public function toString(): string;
}
