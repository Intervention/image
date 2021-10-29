<?php

namespace Intervention\Image;

use Intervention\Image\Exceptions\NotWritableException;

class EncodedImage
{
    protected $data;
    protected $mimetype;

    public function __construct(string $data)
    {
        $this->data = $data;
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

    public function toDataUrl(): string
    {
        return '';
    }

    public function __toString(): string
    {
        return $this->data;
    }
}
