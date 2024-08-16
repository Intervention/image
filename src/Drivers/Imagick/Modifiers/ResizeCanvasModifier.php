<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\ResizeCanvasModifier as GenericResizeCanvasModifier;

class ResizeCanvasModifier extends GenericResizeCanvasModifier implements SpecializedInterface
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

                $delta_width = abs($resize->pivot()->x());
                $delta_height = $resize->pivot()->y() * -1;

                if ($delta_width > 0) {
                    $draw->rectangle(
                        0,
                        $delta_height,
                        $delta_width - 1,
                        $delta_height + $size->height() - 1
                    );
                }

                $draw->rectangle(
                    $size->width() + $delta_width,
                    $delta_height,
                    $resize->width(),
                    $delta_height + $size->height() - 1
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
