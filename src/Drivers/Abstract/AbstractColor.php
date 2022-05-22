<?php

namespace Intervention\Image\Drivers\Abstract;

use Intervention\Image\Interfaces\ColorInterface;

abstract class AbstractColor implements ColorInterface
{
    /**
     * Format color to hexadecimal color code
     *
     * @param  string $prefix
     * @return string
     */
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

    /**
     * Determine if color is greyscale
     *
     * @return boolean
     */
    public function isGreyscale(): bool
    {
        return ($this->red() === $this->green()) && ($this->green() === $this->blue());
    }
}
