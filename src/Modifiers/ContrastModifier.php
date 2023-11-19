<?php

namespace Intervention\Image\Modifiers;

class ContrastModifier extends AbstractModifier
{
    public function __construct(public int $level)
    {
    }
}
