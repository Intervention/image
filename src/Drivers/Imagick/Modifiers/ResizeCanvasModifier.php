<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use Intervention\Image\Drivers\DriverSpecialized;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;

/**
 * @method SizeInterface cropSize(ImageInterface $image)
 * @property mixed $background
 */
class ResizeCanvasModifier extends DriverSpecialized implements ModifierInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $size = $image->size();
        $resize = $this->cropSize($image);

        $background = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->driver()->handleInput($this->background)
        );

        foreach ($image as $frame) {
            $frame->native()->extentImage(
                $resize->width(),
                $resize->height(),
                $resize->pivot()->x(),
                $resize->pivot()->y()
            );

            if ($resize->width() > $size->width()) {
                // fill new emerged background
                $draw = new ImagickDraw();
                $draw->setFillColor($background);

                $delta = abs($resize->pivot()->x());

                if ($delta > 0) {
                    $draw->rectangle(
                        0,
                        0,
                        $delta - 1,
                        $resize->height()
                    );
                }

                $draw->rectangle(
                    $size->width() + $delta,
                    0,
                    $resize->width(),
                    $resize->height()
                );
                $frame->native()->drawImage($draw);
            }

            if ($resize->height() > $size->height()) {
                // fill new emerged background
                $draw = new ImagickDraw();
                $draw->setFillColor($background);

                $delta = abs($resize->pivot()->y());

                if ($delta > 0) {
                    $draw->rectangle(
                        0,
                        0,
                        $resize->width(),
                        $delta - 1
                    );
                }

                $draw->rectangle(
                    0,
                    $size->height() + $delta,
                    $resize->width(),
                    $resize->height()
                );

                $frame->native()->drawImage($draw);
            }
        }

        return $image;
    }
}
