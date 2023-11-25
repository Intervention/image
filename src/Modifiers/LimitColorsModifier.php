<?php

namespace Intervention\Image\Modifiers;

class LimitColorsModifier extends AbstractModifier
{
    public function __construct(
        public int $limit = 0,
        public int $threshold = 256
    ) {
    }
}
