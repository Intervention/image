<?php

namespace Intervention\Image\Drivers\Abstract\Modifiers;

use Intervention\Image\Interfaces\DrawableInterface;
use Intervention\Image\Interfaces\PointInterface;
use Intervention\Image\Traits\CanHandleInput;

class AbstractDrawModifier
{
    use CanHandleInput;

    public function __construct(
        protected PointInterface $position,
        protected DrawableInterface $drawable
    ) {
        //
    }
}
