<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Exceptions\InvalidArgumentException;

class PixelateModifier extends SpecializableModifier
{
    /**
     * @throws InvalidArgumentException
     */
    public function __construct(public int $size)
    {
        if ($this->size < 1) {
            throw new InvalidArgumentException('Invalid pixelation size. Only use int<1, max>');
        }
    }
}
