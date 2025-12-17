<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Exceptions\FontException;
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

        // build full path to font file to make sure to pass absolute path to imageftbbox()
        // because of issues with different GD version behaving differently when passing
        // relative paths to imagettftext()
        $fontPath = $this->font->hasFilename() ? realpath($this->font->filename()) : false;
        if ($this->font->hasFilename() && $fontPath === false) {
            throw new FontException('Font file ' . $this->font->filename() . ' does not exist.');
        }

        foreach ($image as $frame) {
            imagealphablending($frame->native(), true);
            if ($this->font->hasFilename()) {
                foreach ($lines as $line) {
                    foreach ($this->strokeOffsets($this->font) as $offset) {
                        imagettftext(
                            image: $frame->native(),
                            size: $fontProcessor->nativeFontSize($this->font),
                            angle: $this->font->angle() * -1,
                            x: $line->position()->x() + $offset->x(),
                            y: $line->position()->y() + $offset->y(),
                            color: $strokeColor,
                            font_filename: $fontPath,
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
                        font_filename: $fontPath,
                        text: (string) $line
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
     */
    private function gdFont(): int
    {
        if (is_numeric($this->font->filename())) {
            return intval($this->font->filename());
        }

        return 1;
    }
}
