<?php

namespace Intervention\Image\Interfaces;

interface EncodedImageInterface extends GenericDataInterface
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
}
