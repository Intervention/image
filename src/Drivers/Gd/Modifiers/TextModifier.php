<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\TextModifier as GenericTextModifier;

class TextModifier extends GenericTextModifier implements SpecializedInterface
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
        $textColor = $this->gdTextColor($image);
        $strokeColor = $this->gdStrokeColor($image);

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
     * Decode text color in GD compatible format
     *
     * @param ImageInterface $image
     * @return int
     * @throws RuntimeException
     * @throws ColorException
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
     * @param ImageInterface $image
     * @return int
     * @throws RuntimeException
     * @throws ColorException
     */
    protected function gdStrokeColor(ImageInterface $image): int
    {
        if (!$this->font->hasStrokeEffect()) {
            return 0;
        }

        $color = parent::strokeColor();

        if ($color->isTransparent()) {
            throw new ColorException(
                'The stroke color must be fully opaque.'
            );
        }

        return $this
            ->driver()
            ->colorProcessor($image->colorspace())
            ->colorToNative($color);
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
