<?php

namespace Intervention\Image;

use Intervention\Image\Interfaces\EncodedImageInterface;

class EncodedImage extends File implements EncodedImageInterface
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
     * Transform encoded image data into an data uri string
     *
     * @return string
     */
    public function toDataUri(): string
    {
        return sprintf('data:%s;base64,%s', $this->mimetype, base64_encode($this->data));
    }
}
