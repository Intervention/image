<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;

class ResizeModifier implements ModifierInterface
{
    protected $resize;

    public function __construct(SizeInterface $resize)
    {
        $this->resize = $resize;
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            $frame->getCore()->scaleImage(
                $this->resize->getWidth(),
                $this->resize->getHeight()
            );
        }

        return $image;
    }
}
