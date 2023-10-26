<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;

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
        $crop->alignPivotTo($image->size(), $this->position);

        foreach ($image as $frame) {
            $this->cropFrame($frame, $crop);
        }

        return $image;
    }

    protected function cropFrame(FrameInterface $frame, SizeInterface $resizeTo): void
    {
        // create new image
        $modified = imagecreatetruecolor(
            $resizeTo->width(),
            $resizeTo->height()
        );

        // get current image
        $current = $frame->core();

        // preserve transparency
        imagealphablending($modified, false);
        $transIndex = imagecolortransparent($current);

        if ($transIndex != -1) {
            $rgba = imagecolorsforindex($modified, $transIndex);
            $transColor = imagecolorallocatealpha($modified, $rgba['red'], $rgba['green'], $rgba['blue'], 127);
            imagefill($modified, 0, 0, $transColor);
        } else {
            imagesavealpha($modified, true);
        }

        // copy content from resource
        imagecopyresampled(
            $modified,
            $current,
            0,
            0,
            $resizeTo->getPivot()->getX() + $this->offset_x,
            $resizeTo->getPivot()->getY() + $this->offset_y,
            $resizeTo->width(),
            $resizeTo->height(),
            $resizeTo->width(),
            $resizeTo->height(),
        );

        imagedestroy($current);

        if ($transIndex != -1) { // @todo refactor because of duplication
            imagecolortransparent($modified, $transIndex);
            for ($y = 0; $y < $resizeTo->height(); ++$y) {
                for ($x = 0; $x < $resizeTo->width(); ++$x) {
                    if (((imagecolorat($modified, $x, $y) >> 24) & 0x7F) >= 100) {
                        imagesetpixel(
                            $modified,
                            $x,
                            $y,
                            $transIndex
                        );
                    }
                }
            }
        }

        // set new content as recource
        $frame->setCore($modified);
    }
}
