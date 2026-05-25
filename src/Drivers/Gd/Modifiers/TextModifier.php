<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use GdImage;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\FontProcessorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\TextModifier as GenericTextModifier;
use Intervention\Image\Typography\TextBlock;

class TextModifier extends GenericTextModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     *
     * @throws ModifierException
     * @throws StateException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $fontProcessor = $this->driver()->fontProcessor();
        $lines = $fontProcessor->textBlock($this->text, $this->font, $this->position);

        // decode text colors
        $textColor = $this->gdTextColor($image);
        $strokeColor = $this->gdStrokeColor($image);

        foreach ($image as $frame) {
            imagealphablending($frame->native(), true);
            if ($this->font->hasFile()) {
                $this->drawFontFileLines($frame->native(), $lines, $fontProcessor, $textColor, $strokeColor);
            } else {
                $this->drawGdFontLines($frame->native(), $lines, $textColor, $strokeColor);
            }
        }

        return $image;
    }

    /**
     * Draw text lines using a font file
     */
    private function drawFontFileLines(
        GdImage $canvas,
        TextBlock $lines,
        FontProcessorInterface $fontProcessor,
        int $textColor,
        int $strokeColor,
    ): void {
        foreach ($lines as $line) {
            foreach ($this->strokeOffsets($this->font) as $offset) {
                imagettftext(
                    image: $canvas,
                    size: $fontProcessor->nativeFontSize($this->font),
                    angle: $this->font->angle() * -1,
                    x: $line->position()->x() + $offset->x(),
                    y: $line->position()->y() + $offset->y(),
                    color: $strokeColor,
                    font_filename: $this->font->filepath(),
                    text: (string) $line
                );
            }

            imagettftext(
                image: $canvas,
                size: $fontProcessor->nativeFontSize($this->font),
                angle: $this->font->angle() * -1,
                x: $line->position()->x(),
                y: $line->position()->y(),
                color: $textColor,
                font_filename: $this->font->filepath(),
                text: (string) $line
            );
        }
    }

    /**
     * Draw text lines using GD's built-in font
     */
    private function drawGdFontLines(
        GdImage $canvas,
        TextBlock $lines,
        int $textColor,
        int $strokeColor,
    ): void {
        foreach ($lines as $line) {
            foreach ($this->strokeOffsets($this->font) as $offset) {
                imagestring(
                    image: $canvas,
                    font: $this->gdFont(),
                    x: $line->position()->x() + $offset->x(),
                    y: $line->position()->y() + $offset->y(),
                    string: (string) $line,
                    color: $strokeColor
                );
            }

            imagestring(
                image: $canvas,
                font: $this->gdFont(),
                x: $line->position()->x(),
                y: $line->position()->y(),
                string: (string) $line,
                color: $textColor
            );
        }
    }

    /**
     * Decode text color in GD compatible format
     *
     * @throws StateException
     */
    protected function gdTextColor(ImageInterface $image): int
    {
        return $this
            ->driver()
            ->colorProcessor($image)
            ->export(parent::textColor());
    }

    /**
     * Decode color for stroke (outline) effect in GD compatible format
     *
     * @throws StateException
     */
    protected function gdStrokeColor(ImageInterface $image): int
    {
        if (!$this->font->hasStrokeEffect()) {
            return 0;
        }

        $color = parent::strokeColor();

        if ($color->isTransparent()) {
            throw new StateException('The stroke color must be fully opaque');
        }

        return $this
            ->driver()
            ->colorProcessor($image)
            ->export($color);
    }

    /**
     * Return GD's internal font size
     */
    private function gdFont(): int
    {
        if (!in_array($this->font->size(), range(1, 5))) {
            return 1;
        }

        return (int) $this->font->size();
    }
}
