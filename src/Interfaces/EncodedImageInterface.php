<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface EncodedImageInterface extends FileInterface
{
    /**
     * Return Media (MIME) Type of encoded image
     *
     * @return string
     */
    public function mediaType(): string;

    /**
     * Alias of self::mediaType()
     *
     * @return string
     */
    public function mimetype(): string;

    /**
     * Transform encoded image data into an data uri string
     *
     * @return string
     */
    public function toDataUri(): string;
}
