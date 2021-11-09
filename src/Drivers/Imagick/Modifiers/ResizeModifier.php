<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\Abstract\Modifiers\AbstractResizeModifier;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;

class ResizeModifier extends AbstractResizeModifier implements ModifierInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $resize = $this->getResizeSize($image);
        $crop =  $this->getResizeSize($image);
        $shouldCrop = $crop != $image->getSize();
        foreach ($image as $frame) {
            if ($shouldCrop) {
                $frame->getCore()->cropImage(
                    $crop->getWidth(),
                    $crop->getHeight(),
                    $crop->getPivot()->getX(),
                    $crop->getPivot()->getY()
                );
            }

            $frame->getCore()->scaleImage(
                $resize->getWidth(),
                $resize->getHeight()
            );
        }

        return $image;
    }
}
