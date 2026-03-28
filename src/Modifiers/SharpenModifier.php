<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Exceptions\InvalidArgumentException;

class SharpenModifier extends SpecializableModifier
{
    public function __construct(public int $level)
    {
        if ($this->level < 0) {
            throw new InvalidArgumentException('Invalid sharpening level. Only use int<0, max>');
        }
    }
}
