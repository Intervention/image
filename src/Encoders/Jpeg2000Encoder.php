<?php

declare(strict_types=1);

namespace Intervention\Image\Encoders;

use Intervention\Image\Drivers\SpecializableEncoder;

class Jpeg2000Encoder extends SpecializableEncoder
{
    /**
     * Create new encoder object
     *
     * @param null|bool $strip Strip EXIF metadata
     * @return void
     */
    public function __construct(
        public int $quality = self::DEFAULT_QUALITY,
        public ?bool $strip = null
    ) {
        //
    }
}
