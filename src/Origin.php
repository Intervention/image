<?php

namespace Intervention\Image;

class Origin
{
    public function __construct(
        protected string $mimetype,
        protected int $colors
    ) {
    }
}
