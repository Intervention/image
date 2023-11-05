<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Traits\CanBuildNewImage;

class CropModifier implements ModifierInterface
{
    use CanBuildNewImage;

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
            $this->cropFrame($frame, $crop);
        }

        return $image;
    }

    protected function cropFrame(FrameInterface $frame, SizeInterface $resizeTo): void
    {
        // create new image
        $modified = $this->imageFactory()->newCore(
            $resizeTo->width(),
            $resizeTo->height()
        );

        // get original image
        $original = $frame->core();

        // preserve transparency
        $transIndex = imagecolortransparent($original);

        if ($transIndex != -1) {
            $rgba = imagecolorsforindex($modified, $transIndex);
            $transColor = imagecolorallocatealpha($modified, $rgba['red'], $rgba['green'], $rgba['blue'], 127);
            imagefill($modified, 0, 0, $transColor);
            imagecolortransparent($modified, $transColor);
        }

        // copy content from resource
        imagecopyresampled(
            $modified,
            $original,
            0,
            0,
            $resizeTo->pivot()->x() + $this->offset_x,
            $resizeTo->pivot()->y() + $this->offset_y,
            $resizeTo->width(),
            $resizeTo->height(),
            $resizeTo->width(),
            $resizeTo->height(),
        );

        // set new content as recource
        $frame->setCore($modified);
    }
}
