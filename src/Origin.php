<?php

namespace Intervention\Image;

class Origin
{
    /**
     * Create new origin instance
     *
     * @param string $mimetype
     * @return void
     */
    public function __construct(
        protected string $mimetype = 'application/octet-stream'
    ) {
    }

    public function mimetype(): string
    {
        return $this->mimetype;
    }
}
