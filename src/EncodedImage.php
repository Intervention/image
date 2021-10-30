<?php

namespace Intervention\Image;

use Intervention\Image\Exceptions\NotWritableException;

class EncodedImage
{
    protected $data;
    protected $mimetype;

    public function __construct(string $data, string $mimetype = 'application/octet-stream')
    {
        $this->data = $data;
        $this->mimetype = $mimetype;
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
        return sprintf('data:%s;base64,%s', $this->mimetype, base64_encode($this->data));
    }

    public function __toString(): string
    {
        return $this->data;
    }
}
