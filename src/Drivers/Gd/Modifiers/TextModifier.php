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
        
        $strokeLimit = $this->font->strokeLimit();
        $strokeWidth = $this->font->strokeWidth();
        $strokeColor = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->driver()->handleInput($this->font->strokeColor())
        );
        
        if ( $strokeWidth && $strokeWidth > $strokeLimit ){
            $strokeWidth = $strokeLimit;
        }
    
        foreach ($image as $frame) {
            if ($this->font->hasFilename()) {
                foreach ($lines as $line) {
                    imagealphablending($frame->native(), true);
                    
                    if ( $strokeWidth > 0 )
                    {
                        for ($x = -1; $x <= 1; $x++) {
                            for ($y = -1; $y <= 1; $y++) {
                                
                                imagettftext(
                                    $frame->native(),
                                    $fontProcessor->nativeFontSize($this->font),
                                    $this->font->angle() * -1,
                                    $line->position()->x() + $x * $strokeWidth,
                                    $line->position()->y() + $y * $strokeWidth,
                                    $strokeColor,
                                    $this->font->filename(),
                                    (string) $line
                                );
                            }
                        }
                    }
                    
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
                    
                    if ( $strokeWidth > 0 )
                    {
                        for ($x = -1; $x <= 1; $x++) {
                            for ($y = -1; $y <= 1; $y++) {
                                
                                imagestring(
                                    $frame->native(),
                                    $this->gdFont(),
                                    $line->position()->x() + $x * $strokeWidth,
                                    $line->position()->y() + $y * $strokeWidth,
                                    (string) $line,
                                    $color
                                );
                            }
                        }
                    }
                    
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
