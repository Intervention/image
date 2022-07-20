<?php

namespace Intervention\Image\Drivers\Abstract\Modifiers;

use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

abstract class AbstractFitModifier
{
    public function __construct(
        protected int $width,
        protected int $height,
        protected string $position = 'center'
    ) {
        //
    }

    protected function getCropSize(ImageInterface $image): SizeInterface
    {
        $imagesize = $image->getSize();

        $crop = new Rectangle($this->width, $this->height);
        $crop = $crop->contain(
            $imagesize->getWidth(),
            $imagesize->getHeight()
        )->alignPivotTo($imagesize, $this->position);

        return $crop;
    }

    protected function getResizeSize(SizeInterface $size): SizeInterface
    {
        return $size->scale($this->width, $this->height);
    }
}
