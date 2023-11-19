<?php

namespace Intervention\Image\Modifiers;

use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

class PadDownModifier extends PadModifier
{
    public function getCropSize(ImageInterface $image): SizeInterface
    {
        $resize = $this->getResizeSize($image);

        return $image->size()
            ->contain($resize->width(), $resize->height())
            ->alignPivotTo($resize, $this->position);
    }

    public function getResizeSize(ImageInterface $image): SizeInterface
    {
        return (new Rectangle($this->width, $this->height))
                ->resizeDown($image->width(), $image->height());
    }
}
