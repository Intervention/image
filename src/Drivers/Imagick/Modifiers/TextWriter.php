<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\DriverModifier;
use Intervention\Image\Interfaces\ImageInterface;

class TextWriter extends DriverModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $lines = $this->getAlignedTextBlock();
        $font = $this->font;

        foreach ($image as $frame) {
            foreach ($lines as $line) {
                $frame->data()->annotateImage(
                    $font->toImagickDraw($image->colorspace()),
                    $line->getPosition()->x(),
                    $line->getPosition()->y(),
                    $font->getAngle(),
                    $line
                );
            }
        }

        return $image;
    }
}
