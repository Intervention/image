<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Alignment;

class CoverModifier extends SpecializableModifier
{
    /**
     * Create new modifier object
     */
    public function __construct(
        public int $width,
        public int $height,
        public string|Alignment $alignment = Alignment::CENTER
    ) {
        //
    }

    public function getCropSize(ImageInterface $image): SizeInterface
    {
        $imagesize = $image->size();
        $crop = new Rectangle($this->width, $this->height);

        return $crop->contain(
            $imagesize->width(),
            $imagesize->height()
        )->alignPivotTo($imagesize, $this->alignment);
    }

    public function getResizeSize(SizeInterface $size): SizeInterface
    {
        return $size->resize($this->width, $this->height);
    }
}
