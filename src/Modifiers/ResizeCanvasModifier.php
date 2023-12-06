<?php

namespace Intervention\Image\Modifiers;

use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

class ResizeCanvasModifier extends AbstractModifier
{
    public function __construct(
        public int $width,
        public int $height,
        public mixed $background = 'ffffff',
        public string $position = 'center'
    ) {
    }

    public function cropSize(ImageInterface $image): SizeInterface
    {
        return (new Rectangle($this->width, $this->height))
            ->alignPivotTo($image->size(), $this->position);
    }
}
