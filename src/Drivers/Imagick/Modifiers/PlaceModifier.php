<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use Intervention\Image\Drivers\DriverSpecializedModifier;
use Intervention\Image\Interfaces\ImageInterface;

/**
 * @method mixed getPosition(ImageInterface $image, ImageInterface $watermark)
 * @property mixed $element
 * @property string $position
 * @property int $offset_x
 * @property int $offset_y
 */
class PlaceModifier extends DriverSpecializedModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $watermark = $this->driver()->handleInput($this->element);
        $position = $this->getPosition($image, $watermark);

        foreach ($image as $frame) {
            $frame->native()->compositeImage(
                $watermark->core()->native(),
                Imagick::COMPOSITE_DEFAULT,
                $position->x(),
                $position->y()
            );
        }

        return $image;
    }
}
