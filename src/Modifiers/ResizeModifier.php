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
     * @throws InvalidArgumentException
     */
    public function __construct(public ?int $width = null, public ?int $height = null)
    {
        if ($width === null && $height === null) {
            throw new InvalidArgumentException('At least one argument must be provided: width, height, or both.');
        }
    }
}
