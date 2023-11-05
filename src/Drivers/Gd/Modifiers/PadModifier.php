<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Abstract\Modifiers\AbstractPadModifier;
use Intervention\Image\Drivers\Gd\Traits\CanHandleColors;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Traits\CanBuildNewImage;
use Intervention\Image\Traits\CanHandleInput;

class PadModifier extends AbstractPadModifier implements ModifierInterface
{
    use CanHandleInput;
    use CanHandleColors;
    use CanBuildNewImage;

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
        // create new gd image
        $modified = $this->imageFactory()->newCore(
            $resize->width(),
            $resize->height(),
            $background
        );

        // make image area transparent to keep transparency
        // even if background-color is set
        $transparent = imagecolorallocatealpha($modified, 255, 0, 255, 127);
        imagealphablending($modified, false); // do not blend / just overwrite
        imagecolortransparent($modified, $transparent);
        imagefilledrectangle(
            $modified,
            $crop->pivot()->x(),
            $crop->pivot()->y(),
            $crop->pivot()->x() + $crop->width() - 1,
            $crop->pivot()->y() + $crop->height() - 1,
            $transparent
        );

        // copy image from original with blending alpha
        imagealphablending($modified, true);
        imagecopyresampled(
            $modified,
            $frame->core(),
            $crop->pivot()->x(),
            $crop->pivot()->y(),
            0,
            0,
            $crop->width(),
            $crop->height(),
            $frame->size()->width(),
            $frame->size()->height()
        );

        // set new content as recource
        $frame->setCore($modified);
    }
}
