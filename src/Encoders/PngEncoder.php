<?php

declare(strict_types=1);

namespace Intervention\Image\Encoders;

use Intervention\Image\Drivers\SpecializableEncoder;

class PngEncoder extends SpecializableEncoder
{
    /**
     * Create new encoder object
     *
     * @param bool $interlaced
     * @param bool $indexed
     * @return void
     */
    public function __construct(public bool $interlaced = false, public bool $indexed = false)
    {
        //
    }
}
