<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;

class QuantizeColorsModifier extends SpecializableModifier
{
    /**
     * Create new modifier object
     *
     * @param int $limit
     * @param mixed $background
     * @return void
     */
    public function __construct(
        public int $limit,
        public mixed $background = 'ffffff'
    ) {
    }
}
