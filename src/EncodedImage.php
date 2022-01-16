<?php

namespace Intervention\Image;

use Intervention\Image\Exceptions\NotWritableException;

class EncodedImage
{
    public function __construct(
        protected string $data,
        protected string $mimetype = 'application/octet-stream'
    ) {
        //
    }

    public function mimetype(): string
    {
        return $this->mimetype;
    }

    public function save(string $filepath): void
    {
        $saved = @file_put_contents($filepath, (string) $this);
        if ($saved === false) {
            throw new NotWritableException(
                "Can't write image data to path ({$filepath})."
            );
        }
    }

    public function toDataUri(): string
    {
        return sprintf('data:%s;base64,%s', $this->mimetype, base64_encode($this->data));
    }

    public function toString(): string
    {
        return $this->data;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
