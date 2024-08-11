<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;

class ResolutionModifier extends SpecializableModifier
{
    /**
     * Create new modifier object
     *
     * @param float $x
     * @param float $y
     * @return void
     */
    public function __construct(public float $x, public float $y)
    {
    }
}
