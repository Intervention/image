<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Exceptions\InvalidArgumentException;

class SliceAnimationModifier extends SpecializableModifier
{
    public function __construct(public int $offset = 0, public ?int $length = null)
    {
        if ($this->length !== null && $this->length <= 0) {
            throw new InvalidArgumentException('Length must be greater than or equal to 1');
        }
    }
}
