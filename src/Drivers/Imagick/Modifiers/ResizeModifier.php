<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;

class ResizeModifier implements ModifierInterface
{
    /**
     * Target size
     *
     * @var SizeInterface
     */
    protected $target;

    public function __construct(SizeInterface $target)
    {
        $this->target = $target;
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            $frame->getCore()->scaleImage(
                $this->target->getWidth(),
                $this->target->getHeight()
            );
        }

        return $image;
    }
}
