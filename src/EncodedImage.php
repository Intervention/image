<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Interfaces\EncodedImageInterface;

class EncodedImage extends File implements EncodedImageInterface
{
    /**
     * Create new instance
     *
     * @param string $data
     * @param string $mediaType
     */
    public function __construct(
        protected string $data,
        protected string $mediaType = 'application/octet-stream'
    ) {
    }

    /**
     * Return media (mime) type of encoed image data
     *
     * @return string
     */
    public function mediaType(): string
    {
        return $this->mediaType;
    }

    /**
     * Alias of self::mediaType(
     *
     * @return string
     */
    public function mimetype(): string
    {
        return $this->mediaType();
    }

    /**
     * Transform encoded image data into an data uri string
     *
     * @return string
     */
    public function toDataUri(): string
    {
        return sprintf('data:%s;base64,%s', $this->mediaType, base64_encode($this->data));
    }
}
