<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\DriverSpecialized;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;

/**
 * @method SizeInterface getCropSize(ImageInterface $image)
 * @method SizeInterface getResizeSize(SizeInterface $size)
 */
class CoverModifier extends DriverSpecialized implements ModifierInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $crop = $this->getCropSize($image);
        $resize = $this->getResizeSize($crop);

        foreach ($image as $frame) {
            $frame->native()->extentImage(
                $crop->width(),
                $crop->height(),
                $crop->pivot()->x(),
                $crop->pivot()->y()
            );

            $frame->native()->scaleImage(
                $resize->width(),
                $resize->height()
            );
        }

        return $image;
    }
}
