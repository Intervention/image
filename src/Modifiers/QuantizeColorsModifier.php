<?php

namespace Intervention\Image\Modifiers;

class QuantizeColorsModifier extends AbstractModifier
{
    public function __construct(
        public int $limit,
        public mixed $background = 'ffffff'
    ) {
    }
}
