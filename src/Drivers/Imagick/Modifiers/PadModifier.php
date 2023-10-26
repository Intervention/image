<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use ImagickDraw;
use Intervention\Image\Drivers\Abstract\Modifiers\AbstractPadModifier;
use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Drivers\Imagick\Traits\CanHandleColors;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Traits\CanBuildNewImage;
use Intervention\Image\Traits\CanHandleInput;

class PadModifier extends AbstractPadModifier implements ModifierInterface
{
    use CanBuildNewImage;
    use CanHandleInput;
    use CanHandleColors;

    public function apply(ImageInterface $image): ImageInterface
    {
        $resize = $this->getResizeSize($image);
        $crop = $this->getCropSize($image);
        $background = $this->handleInput($this->background);

        foreach ($image as $frame) {
            // resize current core
            $frame->core()->scaleImage(
                $crop->width(),
                $crop->height()
            );

            // create new canvas, to get newly emerged background color
            $canvas = $this->buildBaseCanvas($crop, $resize, $background);

            // place current core onto canvas
            $canvas->compositeImage(
                $frame->core(),
                Imagick::COMPOSITE_DEFAULT,
                $crop->pivot()->getX(),
                $crop->pivot()->getY()
            );

            // replace core
            $frame->core()->destroy();
            $frame->setCore($canvas);
        }

        return $image;
    }

    protected function buildBaseCanvas(SizeInterface $crop, SizeInterface $resize, Color $background): Imagick
    {
        // build base canvas in target size
        $canvas = $this->imageFactory()->newCore(
            $resize->width(),
            $resize->height()
        );

        // draw background color on canvas
        $draw = new ImagickDraw();
        $draw->setFillColor($this->colorToPixel($background, $canvas->getColorspace()));
        $draw->rectangle(0, 0, $canvas->getImageWidth(), $canvas->getImageHeight());
        $canvas->drawImage($draw);

        // make area where image is placed transparent to keep
        // transparency even if background-color is set
        $draw = new ImagickDraw();
        $fill = $background->toHex('#') == '#ff0000' ? '#00ff00' : '#ff0000';
        $draw->setFillColor($fill);
        $draw->rectangle(
            $crop->pivot()->getX(),
            $crop->pivot()->getY(),
            $crop->pivot()->getX() + $crop->width() - 1,
            $crop->pivot()->getY() + $crop->height() - 1
        );
        $canvas->drawImage($draw);
        $canvas->transparentPaintImage($fill, 0, 0, false);

        return $canvas;
    }
}
