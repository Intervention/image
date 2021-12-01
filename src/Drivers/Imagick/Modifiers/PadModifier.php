<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;

class PadModifier implements ModifierInterface
{
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
            //
        }

        return $image;
    }
}
