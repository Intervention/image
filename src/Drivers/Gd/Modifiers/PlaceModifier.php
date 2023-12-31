<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\DriverSpecialized;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

/**
 * @method mixed getPosition(ImageInterface $image, ImageInterface $watermark)
 * @property mixed $element
 * @property string $position
 * @property int $offset_x
 * @property int $offset_y
 */
class PlaceModifier extends DriverSpecialized implements ModifierInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $watermark = $this->driver()->handleInput($this->element);
        $position = $this->getPosition($image, $watermark);

        foreach ($image as $frame) {
            imagealphablending($frame->native(), true);
            imagecopy(
                $frame->native(),
                $watermark->core()->native(),
                $position->x(),
                $position->y(),
                0,
                0,
                $watermark->width(),
                $watermark->height()
            );
        }

        return $image;
    }
}
