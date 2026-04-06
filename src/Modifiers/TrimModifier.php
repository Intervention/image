<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Exceptions\InvalidArgumentException;

class TrimModifier extends SpecializableModifier
{
    /**
     * Create new modifier object.
     *
     * @throws InvalidArgumentException
     */
    public function __construct(public int $tolerance = 0)
    {
        if ($this->tolerance < 0) {
            throw new InvalidArgumentException('Invalid trim tolerance. Only use int<0, max>');
        }
    }
}
