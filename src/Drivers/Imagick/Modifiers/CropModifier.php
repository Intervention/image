<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class CropModifier implements ModifierInterface
{
    public function __construct(
        protected int $width,
        protected int $height,
        protected int $offset_x = 0,
        protected int $offset_y = 0,
        protected string $position = 'top-left'
    ) {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        $crop = new Rectangle($this->width, $this->height);
        $crop->align($this->position);
        $crop->alignPivotTo($image->size(), $this->position);

        foreach ($image as $frame) {
            $frame->core()->extentImage(
                $crop->width(),
                $crop->height(),
                $crop->pivot()->x() + $this->offset_x,
                $crop->pivot()->y() + $this->offset_y
            );
        }

        return $image;
    }
}
