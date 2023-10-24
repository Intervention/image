<?php

namespace Intervention\Image\Drivers\Abstract\Modifiers;

use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Traits\CanCheckType;

abstract class AbstractPadModifier
{
    use CanCheckType;

    public function __construct(
        protected int $width,
        protected int $height,
        protected $background = 'ffffff',
        protected string $position = 'center'
    ) {
        //
    }

    protected function getCropSize(ImageInterface $image): SizeInterface
    {
        return $image->size()
                ->contain($this->width, $this->height)
                ->alignPivotTo($this->getResizeSize($image), $this->position);
    }

    protected function getResizeSize(ImageInterface $image): SizeInterface
    {
        return new Rectangle($this->width, $this->height);
    }
}
