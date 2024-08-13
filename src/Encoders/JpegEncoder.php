<?php

declare(strict_types=1);

namespace Intervention\Image\Encoders;

use Intervention\Image\Drivers\SpecializableEncoder;

class JpegEncoder extends SpecializableEncoder
{
    /**
     * Create new encoder object
     *
     * @param int $quality
     * @param bool $progressive
     * @return void
     */
    public function __construct(
        public int $quality = self::DEFAULT_QUALITY,
        public bool $progressive = false
    ) {
    }
}
