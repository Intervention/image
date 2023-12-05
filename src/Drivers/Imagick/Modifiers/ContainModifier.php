<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use ImagickPixel;
use Intervention\Image\Drivers\DriverSpecializedModifier;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

/**
 * @method SizeInterface getCropSize(ImageInterface $image)
 * @method SizeInterface getResizeSize(ImageInterface $image)
 * @property int $width
 * @property int $height
 * @property mixed $background
 * @property string $position
 */
class ContainModifier extends DriverSpecializedModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $crop = $this->getCropSize($image);
        $resize = $this->getResizeSize($image);
        $transparent = new ImagickPixel('transparent');
        $background = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->driver()->handleInput($this->background)
        );

        foreach ($image as $frame) {
            $frame->native()->scaleImage(
                $crop->width(),
                $crop->height(),
            );

            $frame->native()->setBackgroundColor($transparent);
            $frame->native()->setImageBackgroundColor($transparent);

            $frame->native()->extentImage(
                $resize->width(),
                $resize->height(),
                $crop->pivot()->x() * -1,
                $crop->pivot()->y() * -1
            );

            if ($resize->width() > $crop->width()) {
                // fill new emerged background
                $draw = new ImagickDraw();
                $draw->setFillColor($background);
                if ($crop->pivot()->x() > 0) {
                    $draw->rectangle(
                        0,
                        0,
                        $crop->pivot()->x(),
                        $resize->height()
                    );
                }
                $draw->rectangle(
                    $crop->pivot()->x() + $crop->width(),
                    0,
                    $resize->width(),
                    $resize->height()
                );
                $frame->native()->drawImage($draw);
            }

            if ($resize->height() > $crop->height()) {
                // fill new emerged background
                $draw = new ImagickDraw();
                $draw->setFillColor($background);
                if ($crop->pivot()->y() > 0) {
                    $draw->rectangle(
                        0,
                        0,
                        $resize->width(),
                        $crop->pivot()->y(),
                    );
                }
                $draw->rectangle(
                    0,
                    $crop->pivot()->y() + $crop->height(),
                    $resize->width(),
                    $resize->height()
                );
                $frame->native()->drawImage($draw);
            }
        }

        return $image;
    }
}
