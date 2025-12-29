<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick;

use Imagick;
use ImagickException;
use ImagickPixel;
use Intervention\Image\Colors\Cmyk\Channels\Cyan;
use Intervention\Image\Colors\Cmyk\Channels\Key;
use Intervention\Image\Colors\Cmyk\Channels\Magenta;
use Intervention\Image\Colors\Cmyk\Channels\Yellow;
use Intervention\Image\Colors\Cmyk\Colorspace as Cmyk;
use Intervention\Image\Colors\Rgb\Channels\Alpha;
use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Colors\Rgb\Colorspace as Rgb;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorProcessorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;

class ColorProcessor implements ColorProcessorInterface
{
    public function __construct(protected ColorspaceInterface $colorspace)
    {
        //
    }

    public function colorToNative(ColorInterface $color): ImagickPixel
    {
        if ($this->colorspace instanceof Rgb) {
            $color = $this->colorspace->importColor($color);

            try {
                $pixel = new ImagickPixel(
                    sprintf(
                        "srgba(%s, %s, %s, %s)",
                        $color->channel(Red::class)->value(),
                        $color->channel(Green::class)->value(),
                        $color->channel(Blue::class)->value(),
                        $color->channel(Alpha::class)->value(),
                    )
                );
            } catch (ImagickException $e) {
                throw new DriverException('Failed to create RGB color', previous: $e);
            }

            return $pixel;
        }

        if ($this->colorspace instanceof Cmyk) {
            $color = $this->colorspace->importColor($color);

            try {
                $pixel = new ImagickPixel();
                $pixel->setColorValue(Imagick::COLOR_CYAN, $color->channel(Cyan::class)->normalize());
                $pixel->setColorValue(Imagick::COLOR_MAGENTA, $color->channel(Magenta::class)->normalize());
                $pixel->setColorValue(Imagick::COLOR_YELLOW, $color->channel(Yellow::class)->normalize());
                $pixel->setColorValue(Imagick::COLOR_BLACK, $color->channel(Key::class)->normalize());
            } catch (ImagickException $e) {
                throw new DriverException('Failed to create CMYK color', previous: $e);
            }

            return $pixel;
        }

        throw new NotSupportedException(
            'Colorspace ' . $this->colorspace::class . ' is not supported by driver'
        );
    }

    public function nativeToColor(mixed $native): ColorInterface
    {
        return match ($this->colorspace::class) {
            Cmyk::class => $this->colorspace->colorFromNormalized([
                $native->getColorValue(Imagick::COLOR_CYAN),
                $native->getColorValue(Imagick::COLOR_MAGENTA),
                $native->getColorValue(Imagick::COLOR_YELLOW),
                $native->getColorValue(Imagick::COLOR_BLACK),
            ]),
            Rgb::class => $this->colorspace->colorFromNormalized([
                $native->getColorValue(Imagick::COLOR_RED),
                $native->getColorValue(Imagick::COLOR_GREEN),
                $native->getColorValue(Imagick::COLOR_BLUE),
                $native->getColorValue(Imagick::COLOR_ALPHA),
            ]),
            default => throw new NotSupportedException(
                'Colorspace ' . $this->colorspace::class . ' is not supported by driver'
            )
        };
    }
}
