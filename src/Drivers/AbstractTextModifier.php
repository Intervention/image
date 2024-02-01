<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers;

use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\FontInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Typography\TextBlock;
use Intervention\Image\Typography\Line;

/**
 * @property FontInterface $font
 */
abstract class AbstractTextModifier extends DriverSpecialized implements ModifierInterface
{
    /**
     * Calculate size of bounding box of given text
     *
     * @return Polygon
     */
    abstract protected function boxSize(string $text): Polygon;

    /**
     * Calculates typographical leanding
     *
     * @return int
     */
    public function leadingInPixels(): int
    {
        return intval(round($this->fontSizeInPixels() * $this->font->lineHeight()));
    }

    /**
     * Calculates typographical cap height
     *
     * @return int
     */
    public function capHeight(): int
    {
        return $this->boxSize('T')->height();
    }

    /**
     * Calculates the font size in pixels
     *
     * @return int
     */
    public function fontSizeInPixels(): int
    {
        return $this->boxSize('Hy')->height();
    }

    /**
     * Build TextBlock object from text string and align every line
     * according to text modifier's font object and position.
     *
     * @param Point $position
     * @param string $text
     * @return TextBlock
     */
    public function alignedTextBlock(Point $position, string $text): TextBlock
    {
        $lines = new TextBlock($text);
        $boundingBox = $this->boundingBox($lines, $position);
        $pivot = $boundingBox->last();

        $leading = $this->leadingInPixels();
        $blockWidth = $this->lineWidth($lines->longestLine());

        $x = $pivot->x();
        $y = $this->font->hasFilename() ? $pivot->y() + $this->capHeight() : $pivot->y();
        $x_adjustment = 0;

        foreach ($lines as $line) {
            $line_width = $this->lineWidth($line);
            $x_adjustment = $this->font->alignment() == 'left' ? 0 : $blockWidth - $line_width;
            $x_adjustment = $this->font->alignment() == 'right' ? intval(round($x_adjustment)) : $x_adjustment;
            $x_adjustment = $this->font->alignment() == 'center' ? intval(round($x_adjustment / 2)) : $x_adjustment;
            $position = new Point($x + $x_adjustment, $y);
            $position->rotate($this->font->angle(), $pivot);
            $line->setPosition($position);
            $y += $leading;
        }

        return $lines;
    }

    /**
     * Returns bounding box of the given text block according to text modifier's
     * font settings and given pivot point
     *
     * @param TextBlock $block
     * @param Point|null $pivot
     * @return Polygon
     */
    public function boundingBox(TextBlock $block, ?Point $pivot = null): Polygon
    {
        $pivot = $pivot ? $pivot : new Point();

        // bounding box
        $box = (new Rectangle(
            $this->lineWidth($block->longestLine()),
            $this->leadingInPixels() * ($block->count() - 1) + $this->capHeight()
        ));

        // set pivot
        $box->setPivot($pivot);

        // align
        $box->align($this->font->alignment());
        $box->valign($this->font->valignment());

        $box->rotate($this->font->angle());

        return $box;
    }

    /**
     * Calculates the width of the given line in pixels
     *
     * @param Line $line
     * @return int
     */
    private function lineWidth(Line $line): int
    {
        return $this->boxSize((string) $line)->width();
    }
}
