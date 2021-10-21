<?php

namespace Intervention\Image\Drivers\Abstract;

abstract class AbstractColor
{
    public function toHex(string $prefix = ''): string
    {
        return sprintf(
            '%s%02x%02x%02x',
            $prefix,
            $this->red(),
            $this->green(),
            $this->blue()
        );
    }
}
