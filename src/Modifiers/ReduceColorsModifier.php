<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;

class ReduceColorsModifier extends SpecializableModifier
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
     * Return color in colorspace of image to fill transparent areas.
     *
     * @throws StateException
     */
    protected function backgroundColor(ImageInterface $image): ColorInterface
    {
        return $this->driver()->handleColorInput($this->background)->toColorspace($image->colorspace());
    }
}
