<?php

namespace Intervention\Image\Modifiers;

class QuantizeColorsModifier extends SpecializableModifier
{
    public function __construct(
        public int $limit,
        public mixed $background = 'ffffff'
    ) {
    }
}
