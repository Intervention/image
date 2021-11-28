<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;

class FitModifier implements ModifierInterface
{
    protected $crop;
    protected $resize;

    public function __construct(SizeInterface $crop, SizeInterface $resize)
    {
        $this->crop = $crop;
        $this->resize = $resize;
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            $frame->getCore()->extentImage(
                $this->crop->getWidth(),
                $this->crop->getHeight(),
                $this->crop->getPivot()->getX(),
                $this->crop->getPivot()->getY()
            );
            
            $frame->getCore()->scaleImage(
                $this->resize->getWidth(),
                $this->resize->getHeight()
            );
        }

        return $image;
    }
}
