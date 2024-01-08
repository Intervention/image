<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use Intervention\Image\Drivers\DriverSpecializedModifier;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

/**
 * @method SizeInterface crop(ImageInterface $image)
 * @property int $offset_x
 * @property int $offset_y
 * @property mixed $background
 */
class CropModifier extends DriverSpecializedModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $originalSize = $image->size();
        $crop = $this->crop($image);
        $background = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->driver()->handleInput($this->background)
        );

        $draw = new ImagickDraw();
        $draw->setFillColor($background);

        foreach ($image as $frame) {
            // crop image
            $frame->native()->extentImage(
                $crop->width(),
                $crop->height(),
                $crop->pivot()->x() + $this->offset_x,
                $crop->pivot()->y() + $this->offset_y
            );

            // cover the possible newly created areas with background color
            if ($crop->width() > $originalSize->width()) {
                $draw->rectangle(
                    $originalSize->width() + ($this->offset_x * -1),
                    0,
                    $crop->width(),
                    $crop->height()
                );
                $frame->native()->drawImage($draw);
            }

            // cover the possible newly created areas with background color
            if ($crop->height() > $originalSize->height()) {
                $draw->rectangle(
                    0,
                    $originalSize->height() + ($this->offset_y * -1),
                    $crop->width(),
                    $crop->height()
                );
                $frame->native()->drawImage($draw);
            }

            // cover the possible newly created areas with background color
            if ($this->offset_x < 0) {
                $draw->rectangle(
                    0,
                    0,
                    ($this->offset_x * -1) - 1,
                    $originalSize->height() + ($this->offset_y * -1)
                );
                $frame->native()->drawImage($draw);
            }

            // cover the possible newly created areas with background color
            if ($this->offset_y < 0) {
                $draw->rectangle(
                    0,
                    0,
                    $crop->width(),
                    ($this->offset_y * -1) - 1,
                );
                $frame->native()->drawImage($draw);
            }
        }

        return $image;
    }
}
