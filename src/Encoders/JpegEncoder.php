<?php

declare(strict_types=1);

namespace Intervention\Image\Encoders;

use Intervention\Image\Drivers\SpecializableEncoder;

class JpegEncoder extends SpecializableEncoder
{
    public function __construct(
        public int $quality = self::DEFAULT_QUALITY,
        public bool $progressive = false
    ) {
    }
}
