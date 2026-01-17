<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Exceptions\InvalidArgumentException;

class ResizeModifier extends SpecializableModifier
{
    /**
     * Create new modifier object.
     *
     * @return void
     */
    public function __construct(public ?int $width = null, public ?int $height = null)
    {
        if ($width === null && $height === null) {
            throw new InvalidArgumentException('Pass one of the parameters, either width or height, or both');
        }
    }
}
