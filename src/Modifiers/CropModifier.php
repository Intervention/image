<?php

namespace Intervention\Image\Modifiers;

use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

class CropModifier extends AbstractModifier
{
    public function __construct(
        public int $width,
        public int $height,
        public int $offset_x = 0,
        public int $offset_y = 0,
        public string $position = 'top-left'
    ) {
    }

    public function crop(ImageInterface $image): SizeInterface
    {
        $crop = new Rectangle($this->width, $this->height);
        $crop->align($this->position);

        return $crop->alignPivotTo(
            $image->size(),
            $this->position
        );
    }
}
