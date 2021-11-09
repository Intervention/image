<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Traits\CanResizeGeometrically;

class ResizeModifier implements ModifierInterface
{
    use CanResizeGeometrically;

    protected $target;

    public function __construct(SizeInterface $target)
    {
        $this->target = $target;
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
        return $image->getSize();
    }

    protected function getResizeSize(ImageInterface $image): SizeInterface
    {
        return $this->target;
    }

    protected function modify(FrameInterface $frame, SizeInterface $crop, SizeInterface $resize): void
    {
        // create new image
        $modified = imagecreatetruecolor(
            $resize->getWidth(),
            $resize->getHeight()
        );

        // get current image
        $gd = $frame->getCore();

        // preserve transparency
        $transIndex = imagecolortransparent($gd);

        if ($transIndex != -1) {
            $rgba = imagecolorsforindex($modified, $transIndex);
            $transColor = imagecolorallocatealpha($modified, $rgba['red'], $rgba['green'], $rgba['blue'], 127);
            imagefill($modified, 0, 0, $transColor);
            imagecolortransparent($modified, $transColor);
        } else {
            imagealphablending($modified, false);
            imagesavealpha($modified, true);
        }

        // copy content from resource
        $result = imagecopyresampled(
            $modified,
            $gd,
            $resize->getPivot()->getX(),
            $resize->getPivot()->getY(),
            $crop->getPivot()->getX(),
            $crop->getPivot()->getY(),
            $resize->getWidth(),
            $resize->getHeight(),
            $crop->getWidth(),
            $crop->getHeight()
        );

        imagedestroy($gd);

        // set new content as recource
        $frame->setCore($modified);
    }
}
