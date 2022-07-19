<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Gd\Image;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\PointInterface;
use Intervention\Image\Traits\CanResolveDriverClass;

class PlaceModifier implements ModifierInterface
{
    use CanResolveDriverClass;

    /**
     * Create new modifier
     *
     */
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
        $watermark = $this->decodeWatermark();
        $position = $this->getPosition($image, $watermark);

        foreach ($image as $frame) {
            imagealphablending($frame->getCore(), true);
            imagecopy(
                $frame->getCore(),
                $watermark->getFrame()->getCore(),
                $position->getX(),
                $position->getY(),
                0,
                0,
                $watermark->getWidth(),
                $watermark->getHeight()
            );
        }

        return $image;
    }

    protected function decodeWatermark(): Image
    {
        return $this->resolveDriverClass('InputHandler')->handle($this->element);
    }

    protected function getPosition(ImageInterface $image, ImageInterface $watermark): PointInterface
    {
        $image_size = $image->getSize()->movePivot($this->position, $this->offset_x, $this->offset_y);
        $watermark_size = $watermark->getSize()->movePivot($this->position);

        return $image_size->getRelativePositionTo($watermark_size);
    }
}
