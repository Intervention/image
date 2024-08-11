<?php

declare(strict_types=1);

namespace Intervention\Image\Encoders;

use Intervention\Image\Drivers\SpecializableEncoder;

class HeicEncoder extends SpecializableEncoder
{
    /**
     * Create new encoder object
     *
     * @param int $quality
     * @return void
     */
    public function __construct(public int $quality = self::DEFAULT_QUALITY)
    {
    }
}
