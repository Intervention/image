<?php

namespace Intervention\Image\Drivers\Abstract;

use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\FontInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Typography\TextBlock;

abstract class AbstractTextWriter implements ModifierInterface
{
    public function __construct(
        protected Point $position,
        protected FontInterface $font,
        protected string $text
    ) {
        //
    }

    protected function getFont(): FontInterface
    {
        return $this->font;
    }

    protected function getPosition(): Point
    {
        return $this->position;
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
        $position = $this->getPosition();
        $font = $this->getFont();

        $boundingBox = $lines->getBoundingBox($font, $position);
        $pivot = $boundingBox->last();

        $leading = $font->leadingInPixels();
        $blockWidth = $lines->longestLine()->widthInFont($font);

        $x = $pivot->getX();
        $y = $font->hasFilename() ? $pivot->getY() + $font->capHeight() : $pivot->getY();
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
