<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

class PadDownModifier extends PadModifier
{
    protected function getCropSize(ImageInterface $image): SizeInterface
    {
        $resize = $this->getResizeSize($image);

        return $image->getSize()
            ->contain($resize->width(), $resize->height())
            ->alignPivotTo($resize, $this->position);
    }

    protected function getResizeSize(ImageInterface $image): SizeInterface
    {
        return (new Rectangle($this->width, $this->height))
                ->resizeDown($image->getWidth(), $image->getHeight());
    }
}
