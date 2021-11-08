<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Gd\Image;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Traits\CanResolveDriverClass;

class PlaceModifier implements ModifierInterface
{
    use CanResolveDriverClass;

    protected $element;
    protected $position;
    protected $offset_x;
    protected $offset_y;

    /**
     * Create new modifier
     *
     */
    public function __construct($element, string $position, int $offset_x, int $offset_y)
    {
        $this->element = $element;
        $this->position = $position;
        $this->offset_x = $offset_x;
        $this->offset_y = $offset_y;
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
                $watermark->width(),
                $watermark->height()
            );
        }

        return $image;
    }

    protected function decodeWatermark(): Image
    {
        return $this->resolveDriverClass('InputHandler')->handle($this->element);
    }

    protected function getPosition(Image $image, Image $watermark): Point
    {
        $image_size = $image->getSize()->alignPivot($this->position, $this->offset_x, $this->offset_y);
        $watermark_size = $watermark->getSize()->alignPivot($this->position);

        return $image_size->getRelativePositionTo($watermark_size);
    }
}
