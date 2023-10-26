<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use Intervention\Image\Drivers\Imagick\Image;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\PointInterface;
use Intervention\Image\Traits\CanHandleInput;

class PlaceModifier implements ModifierInterface
{
    use CanHandleInput;

    public function __construct(
        protected $element,
        protected string $position,
        protected int $offset_x,
        protected int $offset_y
    ) {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        $watermark = $this->handleInput($this->element);
        $position = $this->getPosition($image, $watermark);

        foreach ($image as $frame) {
            $frame->getCore()->compositeImage(
                $watermark->frame()->getCore(),
                Imagick::COMPOSITE_DEFAULT,
                $position->getX(),
                $position->getY()
            );
        }

        return $image;
    }

    protected function getPosition(ImageInterface $image, Image $watermark): PointInterface
    {
        $image_size = $image->size()->movePivot($this->position, $this->offset_x, $this->offset_y);
        $watermark_size = $watermark->size()->movePivot($this->position);

        return $image_size->getRelativePositionTo($watermark_size);
    }
}
