<?php

namespace Intervention\Image\Modifiers;

use Intervention\Image\Geometry\Point;

class FillModifier extends AbstractModifier
{
    public function __construct(
        public mixed $filling,
        public ?Point $position = null
    ) {
    }

    /**
     * Determine if the fill modifier has a position
     *
     * @return bool
     */
    public function hasPosition(): bool
    {
        return !empty($this->position);
    }
}
