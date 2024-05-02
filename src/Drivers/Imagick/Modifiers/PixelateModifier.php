<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\PixelateModifier as GenericPixelateModifier;

class PixelateModifier extends GenericPixelateModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            $this->pixelateFrame($frame);
        }

        return $image;
    }

    protected function pixelateFrame(FrameInterface $frame): void
    {
        $size = $frame->size();

        $frame->native()->scaleImage(
            (int) round(max(1, $size->width() / $this->size)),
            (int) round(max(1, $size->height() / $this->size))
        );

        $frame->native()->scaleImage($size->width(), $size->height());
    }
}
