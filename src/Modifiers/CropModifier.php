<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Alignment;
use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

class CropModifier extends SpecializableModifier
{
    /**
     * Create new modifier object
     *
     * @return void
     */
    public function __construct(
        public int $width,
        public int $height,
        public int $x = 0,
        public int $y = 0,
        public mixed $background = null,
        public string|Alignment $alignment = Alignment::TOP_LEFT
    ) {
        //
    }

    public function crop(ImageInterface $image): SizeInterface
    {
        $crop = new Rectangle($this->width, $this->height);
        $crop->movePivot($this->alignment);

        return $crop->alignPivotTo(
            $image->size(),
            $this->alignment
        );
    }

    /**
     * Return color to fill the newly created areas after rotation
     */
    protected function backgroundColor(): ColorInterface
    {
        return $this->driver()->handleColorInput($this->background ?? $this->driver()->config()->backgroundColor);
    }
}
