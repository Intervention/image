<?php

namespace Intervention\Image\Modifiers;

class ResizeModifier extends AbstractModifier
{
    public function __construct(public ?int $width = null, public ?int $height = null)
    {
    }
}
