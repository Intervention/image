<?php

namespace Intervention\Image\Interfaces;

use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Typography\TextBlock;

interface FontProcessorInterface
{
    public function leadingInPixels(): int;
    public function fontSizeInPixels(): int;
    public function capHeight(): int;
    public function boxSize(string $text): Polygon;
    public function alignedTextBlock(Point $position, string $text): TextBlock;
    public function boundingBox(TextBlock $block, Point $pivot = null): Polygon;
}
