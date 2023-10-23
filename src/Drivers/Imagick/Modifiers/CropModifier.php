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
        protected string $position = 'center',
        protected int $offset_x = 0,
        protected int $offset_y = 0
    ) {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        $crop = new Rectangle($this->width, $this->height);
        $crop->align($this->position);
        $crop->alignPivotTo($image->getSize(), $this->position);

        foreach ($image as $frame) {
            $frame->getCore()->extentImage(
                $crop->getWidth(),
                $crop->getHeight(),
                $crop->getPivot()->getX() + $this->offset_x,
                $crop->getPivot()->getY() + $this->offset_y
            );
        }

        return $image;
    }
}
