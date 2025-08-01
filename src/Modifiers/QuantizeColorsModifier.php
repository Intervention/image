<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Exceptions\RuntimeException;

class QuantizeColorsModifier extends SpecializableModifier
{
    /**
     * Create new modifier object
     *
     * @return void
     */
    public function __construct(
        public int $limit,
        public mixed $background = 'transparent'
    ) {
        //
    }

    /**
     * Return color to fill the newly created areas after rotation
     *
     * @throws RuntimeException
     */
    protected function backgroundColor(): ColorInterface
    {
        return $this->driver()->handleColorInput(
            $this->background ?? $this->driver()->config()->backgroundColor
        );
    }
}
