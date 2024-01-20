<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Rgb\Channels;

class Alpha extends Red
{
    public function toString(): string
    {
        return strval(round($this->normalize(), 6));
    }
}
