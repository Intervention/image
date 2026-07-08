<?php

declare(strict_types=1);

namespace Intervention\Image\Encoders;

use Intervention\Image\Drivers\SpecializableEncoder;
use Intervention\Image\Exceptions\InvalidArgumentException;

class JxlEncoder extends SpecializableEncoder
{
    /**
     * Create new encoder object.
     *
     * @param null|bool $strip Strip EXIF metadata
     * @param bool $lossless Encode losslessly, ignoring the quality value
     * @throws InvalidArgumentException
     */
    public function __construct(
        public int $quality = self::DEFAULT_QUALITY,
        public ?bool $strip = null,
        public bool $lossless = false,
    ) {
        if ($quality < 0 || $quality > 100) {
            throw new InvalidArgumentException('Quality must be in range 0 to 100');
        }
    }
}
