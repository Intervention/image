<?php

namespace Intervention\Image\Modifiers;

use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\FontInterface;
use Intervention\Image\Typography\TextBlock;

class TextWriter extends AbstractModifier
{
    public function __construct(
        public Point $position,
        public FontInterface $font,
        public string $text
    ) {
    }

    /**
     * Build TextBlock object from text string and align every line
     * according to text writers font object and position.
     *
     * @return TextBlock
     */
    public function getAlignedTextBlock(): TextBlock
    {
        $lines = new TextBlock($this->text);
        $position = $this->position;
        $font = $this->font;

        $boundingBox = $lines->getBoundingBox($font, $position);
        $pivot = $boundingBox->last();

        $leading = $font->leadingInPixels();
        $blockWidth = $lines->longestLine()->widthInFont($font);

        $x = $pivot->x();
        $y = $font->hasFilename() ? $pivot->y() + $font->capHeight() : $pivot->y();
        $x_adjustment = 0;

        foreach ($lines as $line) {
            $x_adjustment = $font->getAlign() == 'left' ? 0 : $blockWidth - $line->widthInFont($font);
            $x_adjustment = $font->getAlign() == 'right' ? intval(round($x_adjustment)) : $x_adjustment;
            $x_adjustment = $font->getAlign() == 'center' ? intval(round($x_adjustment / 2)) : $x_adjustment;
            $position = new Point($x + $x_adjustment, $y);
            $position->rotate($font->getAngle(), $pivot);
            $line->setPosition($position);
            $y += $leading;
        }

        return $lines;
    }
}
