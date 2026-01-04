<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface EncodedImageInterface extends FileInterface
{
    /**
     * Return Media (MIME) Type of encoded image.
     */
    public function mediaType(): string; // todo: maybe move to FileInterface

    /**
     * Alias of self::mediaType().
     */
    public function mimetype(): string; // todo: maybe move to FileInterface

    /**
     * Transform encoded image data into an data uri string.
     */
    public function toDataUri(): DataUriInterface; // todo: maybe move to FileInterface
}
