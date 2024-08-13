<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;

class ResizeModifier extends SpecializableModifier
{
    /**
     * Create new modifier object
     *
     * @param null|int $width
     * @param null|int $height
     * @return void
     */
    public function __construct(public ?int $width = null, public ?int $height = null)
    {
    }
}
