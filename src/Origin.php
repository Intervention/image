<?php

namespace Intervention\Image;

class Origin
{
    /**
     * Create new origin instance
     *
     * @param string $mediaType
     * @return void
     */
    public function __construct(
        protected string $mediaType = 'application/octet-stream'
    ) {
    }

    public function mediaType(): string
    {
        return $this->mediaType;
    }

    public function mimetype(): string
    {
        return $this->mediaType();
    }
}
