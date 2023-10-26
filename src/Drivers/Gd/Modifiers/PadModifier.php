<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Abstract\Modifiers\AbstractPadModifier;
use Intervention\Image\Drivers\Gd\Traits\CanHandleColors;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Traits\CanHandleInput;

class PadModifier extends AbstractPadModifier implements ModifierInterface
{
    use CanHandleInput;
    use CanHandleColors;

    public function apply(ImageInterface $image): ImageInterface
    {
        $crop = $this->getCropSize($image);
        $resize = $this->getResizeSize($image);
        $background = $this->colorToInteger($this->handleInput($this->background));

        foreach ($image as $frame) {
            $this->modify($frame, $crop, $resize, $background);
        }

        return $image;
    }

    protected function modify(
        FrameInterface $frame,
        SizeInterface $crop,
        SizeInterface $resize,
        int $background
    ): void {
        // create new image
        $modified = imagecreatetruecolor(
            $resize->width(),
            $resize->height()
        );

        imagefill($modified, 0, 0, $background);

        // get current image
        $current = $frame->core();

        // preserve transparency
        imagealphablending($modified, false);
        imagesavealpha($modified, true);

        // copy content from resource
        imagecopyresampled(
            $modified,
            $current,
            $crop->pivot()->getX(),
            $crop->pivot()->getY(),
            0,
            0,
            $crop->width(),
            $crop->height(),
            $frame->size()->width(),
            $frame->size()->height()
        );

        imagedestroy($current);

        // set new content as recource
        $frame->setCore($modified);
    }
}
