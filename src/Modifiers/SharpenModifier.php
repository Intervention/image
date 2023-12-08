<?php

namespace Intervention\Image\Modifiers;

class SharpenModifier extends AbstractModifier
{
    public function __construct(public int $amount)
    {
    }
}
