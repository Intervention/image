<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;

class CropResizeModifier implements ModifierInterface
{
    protected $crop;
    protected $resize;
    protected $position;

    public function __construct(SizeInterface $crop, SizeInterface $resize, string $position = 'top-left')
    {
        $this->crop = $crop;
        $this->resize = $resize;
        $this->position = $position;
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        $shouldCrop = $this->crop != $image->getSize();

        foreach ($image as $frame) {
            if ($shouldCrop) {
                $frame->getCore()->extentImage(
                    $this->crop->getWidth(),
                    $this->crop->getHeight(),
                    $this->crop->getPivot()->getX(),
                    $this->crop->getPivot()->getY()
                );
            }

            $frame->getCore()->scaleImage(
                $this->resize->getWidth(),
                $this->resize->getHeight()
            );
        }

        return $image;
    }
}
