<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface EncodedImageInterface extends FileInterface
{
    /**
     * Return Media (MIME) Type of encoded image.
     */
    public function mediaType(): string;

    /**
     * Alias of self::mediaType().
     */
    public function mimetype(): string;

    /**
     * Transform encoded image data into an data uri string.
     */
    public function toDataUri(): DataUriInterface;

    /**
     * Return base64-encoded string from the current instance.
     */
    public function toBase64(): string;
}
