<?php

namespace Intervention\Image\Typography;

use Intervention\Image\Collection;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Geometry\Size;
use Intervention\Image\Interfaces\FontInterface;

class TextBlock extends Collection
{
    public function __construct(string $text)
    {
        foreach (explode("\n", $text) as $line) {
            $this->push(new Line($line));
        }
    }

    /**
     * Set position of each line in text block
     * according to given font settings.
     *
     * @param FontInterface $font
     * @param Point         $pivot
     * @return TextBlock
     */
    public function alignByFont(FontInterface $font, Point $pivot = null): self
    {
        $pivot = $pivot ? $pivot : new Point();

        $leading = $font->leadingInPixels();
        $x = $pivot->getX();
        $y = $font->hasFilename() ? $pivot->getY() + $font->capHeight() : $pivot->getY();

        $x_adjustment = 0;
        $total_width = $this->longestLine()->widthInFont($font);
        foreach ($this as $line) {
            $x_adjustment = $font->getAlign() == 'left' ? 0 : $total_width - $line->widthInFont($font);
            $x_adjustment = $font->getAlign() == 'right' ? intval(round($x_adjustment)) : $x_adjustment;
            $x_adjustment = $font->getAlign() == 'center' ? intval(round($x_adjustment / 2)) : $x_adjustment;
            $position = new Point($x + $x_adjustment, $y);
            $position->rotate($font->getAngle(), $pivot);
            $line->setPosition($position);
            $y += $leading;
        }

        return $this;
    }

    public function getBoundingBox(FontInterface $font, Point $pivot = null): Polygon
    {
        $pivot = $pivot ? $pivot : new Point();

        // bounding box
        $box = (new Size(
            $this->longestLine()->widthInFont($font),
            $font->leadingInPixels() * ($this->count() - 1) + $font->capHeight()
        ))->toPolygon();

        // set pivot
        $box->setPivotPoint($pivot);

        // align
        $box->align($font->getAlign());
        $box->valign($font->getValign());

        $box->rotate($font->getAngle());

        return $box;
    }

    /**
     * Return array of lines in text block
     *
     * @return array
     */
    public function lines(): array
    {
        return $this->items;
    }

    public function getLine($key): ?Line
    {
        if (!array_key_exists($key, $this->lines())) {
            return null;
        }

        return $this->lines()[$key];
    }

    /**
     * Return line with most characters of text block
     *
     * @return Line
     */
    public function longestLine(): Line
    {
        $lines = $this->lines();
        usort($lines, function ($a, $b) {
            if (mb_strlen($a) === mb_strlen($b)) {
                return 0;
            }
            return (mb_strlen($a) > mb_strlen($b)) ? -1 : 1;
        });

        return $lines[0];
    }
}
