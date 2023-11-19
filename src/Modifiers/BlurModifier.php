<?php

namespace Intervention\Image\Modifiers;

class BlurModifier extends AbstractModifier
{
    public function __construct(protected int $amount)
    {
    }
}
