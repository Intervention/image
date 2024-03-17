<?php

declare(strict_types=1);

namespace Intervention\Image\Encoders;

class WebpEncoder extends SpecializableEncoder
{
    public function __construct(public int $quality = self::DEFAULT_QUALITY)
    {
    }
}
