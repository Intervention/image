<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\Abstract\AbstractTextWriter;
use Intervention\Image\Drivers\Imagick\Font;
use Intervention\Image\Interfaces\ImageInterface;

class TextWriter extends AbstractTextWriter
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $lines = $this->getAlignedTextBlock();
        $font = $this->failIfNotClass($this->getFont(), Font::class);

        foreach ($image as $frame) {
            foreach ($lines as $line) {
                $frame->core()->annotateImage(
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
