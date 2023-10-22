<?php

namespace Intervention\Image;

use Intervention\Image\Exceptions\NotWritableException;

class EncodedImage
{
    /**
     * Create new instance
     *
     * @param  string $data
     * @param  string $mimetype
     */
    public function __construct(
        protected string $data,
        protected string $mimetype = 'application/octet-stream'
    ) {
        //
    }

    /**
     * Return mime type of encoed image data
     *
     * @return string
     */
    public function mimetype(): string
    {
        return $this->mimetype;
    }

    /**
     * Save encoded image data in file system
     *
     * @param  string $filepath
     * @return void
     */
    public function save(string $filepath): void
    {
        $saved = @file_put_contents($filepath, (string) $this);
        if ($saved === false) {
            throw new NotWritableException(
                "Can't write image data to path ({$filepath})."
            );
        }
    }

    /**
     * Transform encoded image data into an data uri string
     *
     * @return string
     */
    public function toDataUri(): string
    {
        return sprintf('data:%s;base64,%s', $this->mimetype, base64_encode($this->data));
    }

    /**
     * Cast encoded image object to string
     *
     * @return string
     */
    public function toString(): string
    {
        return $this->data;
    }

    /**
     * Create file pointer from encoded image
     *
     * @return resource
     */
    public function toFilePointer()
    {
        $pointer = fopen('php://temp', 'rw');
        fputs($pointer, $this->toString());
        rewind($pointer);

        return $pointer;
    }

    /**
     * Return byte size of encoded image
     *
     * @return int
     */
    public function size(): int
    {
        return mb_strlen($this->data);
    }

    /**
     * Cast encoded image object to string
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }
}
