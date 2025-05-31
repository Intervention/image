<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\FontInterface;
use Intervention\Image\Interfaces\PointInterface;

class TextModifier extends SpecializableModifier
{
    /**
     * Create new modifier object
     *
     * @return void
     */
    public function __construct(
        public string $text,
        public PointInterface $position,
        public FontInterface $font
    ) {
        //
    }

    /**
     * Decode text color
     *
     * The text outline effect is drawn with a trick by plotting additional text
     * under the actual text with an offset in the color of the outline effect.
     * For this reason, no colors with transparency can be used for the text
     * color or the color of the stroke effect, as this would be superimposed.
     *
     * @throws RuntimeException
     * @throws ColorException
     */
    protected function textColor(): ColorInterface
    {
        $color = $this->driver()->handleInput($this->font->color());

        if ($this->font->hasStrokeEffect() && $color->isTransparent()) {
            throw new ColorException(
                'The text color must be fully opaque when using the stroke effect.'
            );
        }

        return $color;
    }

    /**
     * Decode outline stroke color
     *
     * @throws RuntimeException
     * @throws ColorException
     */
    protected function strokeColor(): ColorInterface
    {
        $color = $this->driver()->handleInput($this->font->strokeColor());

        if ($color->isTransparent()) {
            throw new ColorException(
                'The stroke color must be fully opaque.'
            );
        }

        return $color;
    }

    /**
     * Return array of offset points to draw text stroke effect below the actual text
     *
     * @return array<PointInterface>
     */
    protected function strokeOffsets(FontInterface $font): array
    {
        $offsets = [];

        if ($font->strokeWidth() <= 0) {
            return $offsets;
        }

        for ($x = $font->strokeWidth() * -1; $x <= $font->strokeWidth(); $x++) {
            for ($y = $font->strokeWidth() * -1; $y <= $font->strokeWidth(); $y++) {
                $offsets[] = new Point($x, $y);
            }
        }

        return $offsets;
    }
}
