<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\AbstractTextModifier;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Exceptions\RuntimeException;
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

        // decode text colors
        $textColor = $this->textColor($image);
        $strokeColor = $this->strokeColor($image);

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
                        $textColor,
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
                            $strokeColor
                        );
                    }

                    imagestring(
                        $frame->native(),
                        $this->gdFont(),
                        $line->position()->x(),
                        $line->position()->y(),
                        (string) $line,
                        $textColor
                    );
                }
            }
        }

        return $image;
    }

    /**
     * Decode text color
     *
     * The text outline effect is drawn with a trick by plotting additional text
     * under the actual text with an offset in the color of the outline effect.
     * For this reason, no colors with transparency can be used for the text
     * color or the color of the stroke effect, as this would be superimposed.
     *
     * @param ImageInterface $image
     * @throws RuntimeException
     * @throws ColorException
     * @return int
     */
    protected function textColor(ImageInterface $image): int
    {
        $color = $this->driver()->handleInput($this->font->color());

        if ($this->font->hasStrokeEffect() && $color->isTransparent()) {
            throw new ColorException(
                'The text color must be fully opaque when using the stroke effect.'
            );
        }

        return $this->driver()->colorProcessor($image->colorspace())->colorToNative($color);
    }

    /**
     * Decode outline stroke color
     *
     * @param ImageInterface $image
     * @throws RuntimeException
     * @throws ColorException
     * @return int
     */
    protected function strokeColor(ImageInterface $image): int
    {
        if (!$this->font->hasStrokeEffect()) {
            return 0;
        }

        $color = $this->driver()->handleInput($this->font->strokeColor());

        if ($color->isTransparent()) {
            throw new ColorException(
                'The stroke color must be fully opaque.'
            );
        }

        return $this->driver()->colorProcessor($image->colorspace())->colorToNative($color);
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
