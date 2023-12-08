<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\DriverSpecializedModifier;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

/**
 * @property null|int $width
 * @property null|int $height
 */
class ResizeModifier extends DriverSpecializedModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $resizeTo = $this->getAdjustedSize($image);

        foreach ($image as $frame) {
            $frame->native()->scaleImage(
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
