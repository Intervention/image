<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use ImagickDraw;
use ImagickPixel;
use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Drivers\DriverModifier;
use Intervention\Image\Drivers\Imagick\Core;
use Intervention\Image\Image;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Modifiers\FillModifier;

class PadModifier extends DriverModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $crop = $this->getCropSize($image);
        $resize = $this->getResizeSize($image);
        $background = $this->driver()->handleInput($this->background);

        $imagick = new Imagick();
        foreach ($image as $frame) {
            // resize current core
            $frame->data()->scaleImage(
                $crop->width(),
                $crop->height()
            );

            // create new canvas, to get newly emerged background color
            $canvas = $this->buildBaseCanvas($crop, $resize, $background);

            // place current core onto canvas
            $canvas->compositeImage(
                $frame->data(),
                Imagick::COMPOSITE_DEFAULT,
                $crop->pivot()->x(),
                $crop->pivot()->y()
            );

            $imagick->addImage($canvas);
        }

        return new Image(
            $image->driver(),
            new Core($imagick),
            $image->exif()
        );
    }

    protected function buildBaseCanvas(SizeInterface $crop, SizeInterface $resize, Color $background): Imagick
    {
        // build base canvas in target size
        $canvas = $this->driver()->createImage(
            $resize->width(),
            $resize->height()
        )->modify(
            new FillModifier($background)
        )->core()->native();

        // make area where image is placed transparent to keep
        // transparency even if background-color is set
        $draw = new ImagickDraw();
        $fill = $background->toHex('#') == '#ff0000' ? '#00ff00' : '#ff0000';
        $draw->setFillColor(new ImagickPixel($fill));
        $draw->rectangle(
            $crop->pivot()->x(),
            $crop->pivot()->y(),
            $crop->pivot()->x() + $crop->width() - 1,
            $crop->pivot()->y() + $crop->height() - 1
        );
        $canvas->drawImage($draw);
        $canvas->transparentPaintImage($fill, 0, 0, false);

        return $canvas;
    }
}
