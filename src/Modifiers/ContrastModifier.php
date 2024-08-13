<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;

class ContrastModifier extends SpecializableModifier
{
    /**
     * Create new modifier object
     *
     * @param int $level
     * @return void
     */
    public function __construct(public int $level)
    {
    }
}
