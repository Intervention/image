<?php

namespace Intervention\Image\Typography;

use Intervention\Image\Collection;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\FontInterface;

class TextBlock extends Collection
{
    public function __construct(string $text)
    {
        foreach (explode("\n", $text) as $line) {
            $this->push(new Line($line));
        }
    }

    public function getBoundingBox(FontInterface $font, Point $pivot = null): Polygon
    {
        $pivot = $pivot ? $pivot : new Point();

        // bounding box
        $box = (new Rectangle(
            $this->longestLine()->widthInFont($font),
            $font->leadingInPixels() * ($this->count() - 1) + $font->capHeight()
        ));

        // set pivot
        $box->setPivot($pivot);

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
