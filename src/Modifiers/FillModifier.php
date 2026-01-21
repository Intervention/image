<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\PointInterface;

class FillModifier extends SpecializableModifier
{
    public function __construct(
        public string|ColorInterface $color,
        public ?PointInterface $position = null
    ) {
        //
    }

    /**
     * Determine if the modifier has a position defined.
     */
    public function hasPosition(): bool
    {
        return $this->position instanceof PointInterface;
    }

    /**
     * Return filling color object:
     */
    protected function color(): ColorInterface
    {
        return $this->driver()->handleColorInput($this->color);
    }
}
