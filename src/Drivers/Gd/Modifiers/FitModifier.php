<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Geometry\Resizer;
use Intervention\Image\Geometry\Size;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Traits\CanResizeGeometrically;

/*

# contain
1. Scale (keep aspect ratio) Original to fit Target
2. Scale (keep aspect ratio) Up/Down to fit Target (obsolete)

# cover
1. Scale (keep aspect ratio) Target to fit Original
2. Scale (keep aspect ratio) Up/Down to fit Target

 */

class FitModifier extends ResizeModifier implements ModifierInterface
{
    use CanResizeGeometrically;

    protected $target;
    protected $position;

    public function __construct(SizeInterface $target, string $position = 'top-left')
    {
        $this->target = $target;
        $this->position = $position;
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        $crop = $this->getCropSize($image);
        $resize = $this->getResizeSize($image);

        foreach ($image as $frame) {
            $this->modify($frame, $crop, $resize);
        }

        return $image;
    }

    protected function getCropSize(ImageInterface $image): SizeInterface
    {
        $size = $this->resizeGeometrically($this->target)
                ->toWidth($image->width())
                ->toHeight($image->height())
                ->scale();

        return $size->alignPivotTo(
            $image->getSize()->alignPivot($this->position),
            $this->position
        );
    }

    protected function getResizeSize(ImageInterface $image): SizeInterface
    {
        return $this->resizeGeometrically($this->getCropSize($image))
                ->toWidth($this->target->getWidth())
                ->toHeight($this->target->getHeight())
                ->scale();
    }
}
