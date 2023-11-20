<?php

namespace Intervention\Image\Interfaces;

interface EncodedImageInterface extends FileInterface
{
    /**
     * Return Media (MIME) Type of encoded image
     *
     * @return string
     */
    public function mimetype(): string;

    /**
     * Turn encoded image into DataUri format
     *
     * @return string
     */
    public function toDataUri(): string;

    /**
     * Save encoded image in filesystem
     *
     * @param string $filepath
     * @return void
     */
    public function save(string $filepath): void;

    /**
     * Cast encoded image to string
     *
     * @return string
     */
    public function toString(): string;

    /**
     * Return file pointer of encoded image data
     *
     * @return resource
     */
    public function toFilePointer();

    /**
     * Return size in bytes of encoded image
     *
     * @return int
     */
    public function size(): int;

    /**
     * Cast encoded image data to string
     *
     * @return string
     */
    public function __toString(): string;
}
