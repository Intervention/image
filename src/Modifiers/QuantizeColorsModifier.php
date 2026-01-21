<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ColorInterface;

class QuantizeColorsModifier extends SpecializableModifier
{
    /**
     * Create new modifier object.
     */
    public function __construct(
        public int $limit,
        public string|ColorInterface $background = 'transparent'
    ) {
        //
    }

    /**
     * Return color to fill the newly created areas after rotation.
     *
     * @throws StateException
     */
    protected function backgroundColor(): ColorInterface
    {
        return $this->driver()->handleColorInput($this->background); // todo: convert to image's colorspace
    }
}
