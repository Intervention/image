<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\DriverSpecialized;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\FontInterface;
use Intervention\Image\Interfaces\ModifierInterface;

/**
 * @property Point $position
 * @property string $text
 * @property FontInterface $font
 */
class TextModifier extends DriverSpecialized implements ModifierInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $fontProcessor = $this->driver()->fontProcessor();
        $lines = $fontProcessor->textBlock($this->text, $this->font, $this->position);
        $color = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->driver()->handleInput($this->font->color())
        );

        $outlineColor = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->driver()->handleInput($this->font->strokeColor())
        );
        $outlineWidth = $this->font->strokeWidth();

        foreach ($image as $frame) {
            if ($this->font->hasFilename()) {
                foreach ($lines as $line) {
                    // Draw the outline around the text
                    for ($ox = -$outlineWidth; $ox <= $outlineWidth; $ox++) {
                        for ($oy = -$outlineWidth; $oy <= $outlineWidth; $oy++) {
                            if ($ox !== 0 || $oy !== 0) { // Skip drawing the main text
                                imagealphablending($frame->native(), true);
                                
                                imagettftext(
                                    $frame->native(), 
                                    $this->adjustedFontSize(), 
                                    $this->font->angle() * -1, 
                                    $line->position()->x() + $ox, 
                                    $line->position()->y() + $oy, 
                                    $outlineColor, 
                                    $this->font->filename(), 
                                    (string) $line
                                );
                            }
                        }
                    }
                    imagealphablending($frame->native(), true);
                    // Draw the main text in the center
                    imagettftext(
                        $frame->native(),
                        $fontProcessor->nativeFontSize($this->font),
                        $this->font->angle() * -1,
                        $line->position()->x(),
                        $line->position()->y(),
                        $color,
                        $this->font->filename(),
                        (string) $line
                    );
                }
                
                
                
            } else {
                foreach ($lines as $line) {
                    imagestring(
                        $frame->native(),
                        $this->gdFont(),
                        $line->position()->x(),
                        $line->position()->y(),
                        (string) $line,
                        $color
                    );
                }
            }
        }

        return $image;
    }

     /**
     * Return GD's internal font size (if no ttf file is set)
     *
     * @return int
     */
    private function gdFont(): int
    {
        if (is_numeric($this->font->filename())) {
            return intval($this->font->filename());
        }

        return 1;
    }
}
