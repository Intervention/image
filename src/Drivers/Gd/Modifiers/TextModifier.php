<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\TextModifier as GenericTextModifier;

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
                foreach ($lines as $line) {
                    foreach ($this->strokeOffsets($this->font) as $offset) {
                        imagettftext(
                            image: $frame->native(),
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
                        image: $frame->native(),
                        size: $fontProcessor->nativeFontSize($this->font),
                        angle: $this->font->angle() * -1,
                        x: $line->position()->x(),
                        y: $line->position()->y(),
                        color: $textColor,
                        font_filename: $this->font->filepath(),
                        text: (string) $line
                    );
                }
            } else {
                foreach ($lines as $line) {
                    foreach ($this->strokeOffsets($this->font) as $offset) {
                        imagestring(
                            image: $frame->native(),
                            font: $this->gdFont(),
                            x: $line->position()->x() + $offset->x(),
                            y: $line->position()->y() + $offset->y(),
                            string: (string) $line,
                            color: $strokeColor
                        );
                    }

                    imagestring(
                        image: $frame->native(),
                        font: $this->gdFont(),
                        x: $line->position()->x(),
                        y: $line->position()->y(),
                        string: (string) $line,
                        color: $textColor
                    );
                }
            }
        }

        return $image;
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
            ->colorProcessor($image->colorspace())
            ->colorToNative(parent::textColor());
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
            ->colorProcessor($image->colorspace())
            ->colorToNative($color);
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
