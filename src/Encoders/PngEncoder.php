<?php

declare(strict_types=1);

namespace Intervention\Image\Encoders;

use Intervention\Image\Drivers\SpecializableEncoder;

class PngEncoder extends SpecializableEncoder
{
    public function __construct(public bool $interlaced = false)
    {
    }
}
