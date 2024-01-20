<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use ImagickPixel;
use Intervention\Image\Drivers\DriverSpecialized;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;

/**
 * @method SizeInterface crop(ImageInterface $image)
 * @property int $offset_x
 * @property int $offset_y
 * @property mixed $background
 */
class CropModifier extends DriverSpecialized implements ModifierInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $originalSize = $image->size();
        $crop = $this->crop($image);
        $background = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->driver()->handleInput($this->background)
        );

        $transparent = new ImagickPixel('transparent');

        $draw = new ImagickDraw();
        $draw->setFillColor($background);

        foreach ($image as $frame) {
            $frame->native()->setBackgroundColor($transparent);
            $frame->native()->setImageBackgroundColor($transparent);

            // crop image
            $frame->native()->extentImage(
                $crop->width(),
                $crop->height(),
                $crop->pivot()->x() + $this->offset_x,
                $crop->pivot()->y() + $this->offset_y
            );

            // repage
            $frame->native()->setImagePage(
                $crop->width(),
                $crop->height(),
                0,
                0,
            );

            // cover the possible newly created areas with background color
            if ($crop->width() > $originalSize->width() || $this->offset_x > 0) {
                $draw->rectangle(
                    $originalSize->width() + ($this->offset_x * -1) - $crop->pivot()->x(),
                    0,
                    $crop->width(),
                    $crop->height()
                );
            }

            // cover the possible newly created areas with background color
            if ($crop->height() > $originalSize->height() || $this->offset_y > 0) {
                $draw->rectangle(
                    ($this->offset_x * -1) - $crop->pivot()->x(),
                    $originalSize->height() + ($this->offset_y * -1) - $crop->pivot()->y(),
                    ($this->offset_x * -1) + $originalSize->width() - 1 - $crop->pivot()->x(),
                    $crop->height()
                );
            }

            // cover the possible newly created areas with background color
            if ((($this->offset_x * -1) - $crop->pivot()->x() - 1) > 0) {
                $draw->rectangle(
                    0,
                    0,
                    ($this->offset_x * -1) - $crop->pivot()->x() - 1,
                    $crop->height()
                );
            }

            // cover the possible newly created areas with background color
            if ((($this->offset_y * -1) - $crop->pivot()->y() - 1) > 0) {
                $draw->rectangle(
                    ($this->offset_x * -1) - $crop->pivot()->x(),
                    0,
                    ($this->offset_x * -1) + $originalSize->width() - $crop->pivot()->x() - 1,
                    ($this->offset_y * -1) - $crop->pivot()->y() - 1,
                );
            }

            $frame->native()->drawImage($draw);
        }

        return $image;
    }
}
