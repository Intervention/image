<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Abstract\Modifiers\AbstractPadModifier;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Traits\CanHandleInput;

class PadModifier extends AbstractPadModifier implements ModifierInterface
{
    use CanHandleInput;

    public function apply(ImageInterface $image): ImageInterface
    {
        $crop = $this->getCropSize($image);
        $resize = $this->getResizeSize($image);
        $background = $this->handleInput($this->background);

        foreach ($image as $frame) {
            $this->modify($frame, $crop, $resize, $background);
        }

        return $image;
    }

    protected function modify(
        FrameInterface $frame,
        SizeInterface $crop,
        SizeInterface $resize,
        ColorInterface $background
    ): void {
        // create new image
        $modified = imagecreatetruecolor(
            $resize->getWidth(),
            $resize->getHeight()
        );

        imagefill($modified, 0, 0, $background->toInt());

        // get current image
        $current = $frame->getCore();

        // preserve transparency
        imagealphablending($modified, false);
        imagesavealpha($modified, true);

        // copy content from resource
        imagecopyresampled(
            $modified,
            $current,
            $crop->getPivot()->getX(),
            $crop->getPivot()->getY(),
            0,
            0,
            $crop->getWidth(),
            $crop->getHeight(),
            $frame->getSize()->getWidth(),
            $frame->getSize()->getHeight()
        );

        imagedestroy($current);

        // set new content as recource
        $frame->setCore($modified);
    }
}
