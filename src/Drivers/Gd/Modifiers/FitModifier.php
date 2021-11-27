<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Traits\CanHandleInput;
use Intervention\Image\Traits\CanResizeGeometrically;

class FitModifier implements ModifierInterface
{
    use CanHandleInput;

    protected $crop;
    protected $resize;
    protected $backgroundColor;

    public function __construct(SizeInterface $crop, SizeInterface $resize, $backgroundColor = null)
    {
        $this->crop = $crop;
        $this->resize = $resize;
        $this->backgroundColor = $backgroundColor;
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            $this->modify($frame);
        }

        return $image;
    }

    protected function modify(FrameInterface $frame): void
    {
        // create new image
        $modified = imagecreatetruecolor(
            $this->resize->getWidth(),
            $this->resize->getHeight()
        );

        $color = $this->handleInput($this->backgroundColor);

        imagefill($modified, 0, 0, $color->toInt());

        // get current image
        $current = $frame->getCore();

        // preserve transparency
        $transIndex = imagecolortransparent($current);

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
        imagecopyresampled(
            $modified,
            $current,
            $this->crop->getPivot()->getX(),
            $this->crop->getPivot()->getY(),
            0,
            0,
            $this->crop->getWidth(),
            $this->crop->getHeight(),
            $frame->getSize()->getWidth(),
            $frame->getSize()->getHeight()
        );

        imagedestroy($current);

        // set new content as recource
        $frame->setCore($modified);
    }
}
