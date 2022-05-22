<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Abstract\Modifiers\AbstractFitModifier;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;

class FitModifier extends AbstractFitModifier implements ModifierInterface
{
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
        $modified = imagecreatetruecolor(
            $resize->getWidth(),
            $resize->getHeight()
        );

        // get current image
        $current = $frame->getCore();

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
            $crop->getPivot()->getX(),
            $crop->getPivot()->getY(),
            $resize->getWidth(),
            $resize->getHeight(),
            $crop->getWidth(),
            $crop->getHeight()
        );

        imagedestroy($current);

        if ($transIndex != -1) { // @todo refactor because of duplication
            imagecolortransparent($modified, $transIndex);
            for ($y = 0; $y < $resize->getHeight(); ++$y) {
                for ($x = 0; $x < $resize->getWidth(); ++$x) {
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

        // set new content as resource
        $frame->setCore($modified);
    }
}
