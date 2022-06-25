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

    public function getTextBlock(): TextBlock
    {
        return new TextBlock($this->text);
    }
}
