<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Abstract\AbstractTextWriter;
use Intervention\Image\Drivers\Gd\Font;
use Intervention\Image\Drivers\Gd\Traits\CanHandleColors;
use Intervention\Image\Interfaces\ImageInterface;

class TextWriter extends AbstractTextWriter
{
    use CanHandleColors;

    public function apply(ImageInterface $image): ImageInterface
    {
        $lines = $this->getAlignedTextBlock();
        $font = $this->failIfNotClass($this->getFont(), Font::class);
        $color = $this->colorToInteger($font->getColor());

        foreach ($image as $frame) {
            if ($this->font->hasFilename()) {
                foreach ($lines as $line) {
                    imagettftext(
                        $frame->core(),
                        $font->getSize(),
                        $font->getAngle() * (-1),
                        $line->getPosition()->x(),
                        $line->getPosition()->y(),
                        $color,
                        $font->getFilename(),
                        $line
                    );
                }
            } else {
                foreach ($lines as $line) {
                    imagestring(
                        $frame->core(),
                        $font->getGdFont(),
                        $line->getPosition()->x(),
                        $line->getPosition()->y(),
                        $line,
                        $color
                    );
                }
            }
        }

        return $image;
    }
}
