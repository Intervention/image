<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;

class ResizeModifier implements ModifierInterface
{
    public function __construct(protected ?int $width = null, protected ?int $height = null)
    {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        $resizeTo = $this->getAdjustedSize($image);

        foreach ($image as $frame) {
            $frame->core()->scaleImage(
                $resizeTo->width(),
                $resizeTo->height()
            );
        }

        return $image;
    }

    protected function getAdjustedSize(ImageInterface $image): SizeInterface
    {
        return $image->size()->resize($this->width, $this->height);
    }
}
