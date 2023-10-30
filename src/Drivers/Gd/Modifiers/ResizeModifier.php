<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;

class ResizeModifier implements ModifierInterface
{
    public function __construct(protected ?int $width = null, protected ?int $height = null)
    {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        $resizeTo = $this->getAdjustedSize($image);

        foreach ($image as $frame) {
            $this->resizeFrame($frame, $resizeTo);
        }

        return $image;
    }

    protected function getAdjustedSize(ImageInterface $image): SizeInterface
    {
        return $image->size()->resize($this->width, $this->height);
    }

    protected function resizeFrame(FrameInterface $frame, SizeInterface $resizeTo): void
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
            $resizeTo->pivot()->x(),
            $resizeTo->pivot()->y(),
            0,
            0,
            $resizeTo->width(),
            $resizeTo->height(),
            $frame->size()->width(),
            $frame->size()->height()
        );

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
