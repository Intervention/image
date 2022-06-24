<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use ImagickDraw;
use Intervention\Image\Drivers\Abstract\Modifiers\AbstractPadModifier;
use Intervention\Image\Drivers\Imagick\Color;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Traits\CanBuildNewImage;
use Intervention\Image\Traits\CanHandleInput;

class PadModifier extends AbstractPadModifier implements ModifierInterface
{
    use CanBuildNewImage;
    use CanHandleInput;

    public function apply(ImageInterface $image): ImageInterface
    {
        $resize = $this->getResizeSize($image);
        $crop = $this->getCropSize($image);
        $background = $this->handleInput($this->background);

        if (!is_a($background, Color::class)) {
            throw new DecoderException('Unable to decode backgroud color.');
        }

        foreach ($image as $frame) {
            // resize current core
            $frame->getCore()->scaleImage(
                $crop->getWidth(),
                $crop->getHeight()
            );

            // create new canvas, to get newly emerged background color
            $canvas = $this->buildBaseCanvas($crop, $resize, $background);

            // place current core onto canvas
            $canvas->compositeImage(
                $frame->getCore(),
                Imagick::COMPOSITE_DEFAULT,
                $crop->getPivot()->getX(),
                $crop->getPivot()->getY()
            );

            // replace core
            $frame->getCore()->destroy();
            $frame->setCore($canvas);
        }

        return $image;
    }

    protected function buildBaseCanvas(SizeInterface $crop, SizeInterface $resize, Color $background): Imagick
    {
        // build base canvas in target size
        $canvas = $this->imageFactory()->newCore(
            $resize->getWidth(),
            $resize->getHeight()
        );

        // draw background color on canvas
        $draw = new ImagickDraw();
        $draw->setFillColor($background->getPixel());
        $draw->rectangle(0, 0, $canvas->getImageWidth(), $canvas->getImageHeight());
        $canvas->drawImage($draw);

        // make area where image is placed transparent to keep
        // transparency even if background-color is set
        $draw = new ImagickDraw();
        $fill = $background->toHex('#') == '#ff0000' ? '#00ff00' : '#ff0000';
        $draw->setFillColor($fill);
        $draw->rectangle(
            $crop->getPivot()->getX(),
            $crop->getPivot()->getY(),
            $crop->getPivot()->getX() + $crop->getWidth() - 1,
            $crop->getPivot()->getY() + $crop->getHeight() - 1
        );
        $canvas->drawImage($draw);
        $canvas->transparentPaintImage($fill, 0, 0, false);

        return $canvas;
    }
}
