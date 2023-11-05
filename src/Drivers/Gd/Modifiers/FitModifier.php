<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Abstract\Modifiers\AbstractFitModifier;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Traits\CanBuildNewImage;

class FitModifier extends AbstractFitModifier implements ModifierInterface
{
    use CanBuildNewImage;

    public function apply(ImageInterface $image): ImageInterface
    {
        $crop = $this->getCropSize($image);
        $resize = $this->getResizeSize($crop);

        foreach ($image as $frame) {
            $this->modifyFrame($frame, $crop, $resize);
        }

        return $image;
    }

    protected function modifyFrame(FrameInterface $frame, SizeInterface $crop, SizeInterface $resize): void
    {
        // create new image
        $modified = $this->imageFactory()->newCore(
            $resize->width(),
            $resize->height()
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
            $crop->pivot()->x(),
            $crop->pivot()->y(),
            $resize->width(),
            $resize->height(),
            $crop->width(),
            $crop->height()
        );

        // set new content as resource
        $frame->setCore($modified);
    }
}
