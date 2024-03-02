<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\AbstractTextModifier;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\FontInterface;
use Intervention\Image\Interfaces\ModifierInterface;

/**
 * @property Point $position
 * @property string $text
 * @property FontInterface $font
 */
class TextModifier extends AbstractTextModifier implements ModifierInterface
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

        // decode text color
        $color = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->driver()->handleInput($this->font->color())
        );

        // decode color for outline stroke effect only if the stroke width is above 0.
        // If the width is below 0 and not applicable, no outline effect is drawn and
        // the stroke color remains unused. The default value is therefore set to 0.
        $strokeColor = match (true) {
            $this->font->strokeWidth() > 0 => $this
                ->driver()
                ->colorProcessor($image->colorspace())
                ->colorToNative(
                    $this->driver()->handleInput($this->font->strokeColor())
                ),
            default => 0,
        };

        foreach ($image as $frame) {
            imagealphablending($frame->native(), true);
            if ($this->font->hasFilename()) {
                foreach ($lines as $line) {
                    foreach ($this->strokeOffsets($this->font) as $offset) {
                        imagettftext(
                            $frame->native(),
                            $fontProcessor->nativeFontSize($this->font),
                            $this->font->angle() * -1,
                            $line->position()->x() + $offset->x(),
                            $line->position()->y() + $offset->y(),
                            $strokeColor,
                            $this->font->filename(),
                            (string) $line
                        );
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
                    foreach ($this->strokeOffsets($this->font) as $offset) {
                        imagestring(
                            $frame->native(),
                            $this->gdFont(),
                            $line->position()->x() + $offset->x(),
                            $line->position()->y() + $offset->y(),
                            (string) $line,
                            $color
                        );
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
