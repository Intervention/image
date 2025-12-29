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
use Intervention\Image\Colors\Hsl\Colorspace as Hsl;
use Intervention\Image\Colors\Hsv\Colorspace as Hsv;
use Intervention\Image\Colors\Oklab\Colorspace as Oklab;
use Intervention\Image\Colors\Oklch\Colorspace as Oklch;
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
        $color = $this->colorspace->importColor($color);

        if ($this->colorspace instanceof Cmyk) {
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

        $color = $color->toColorspace(Rgb::class);

        try {
            return new ImagickPixel(
                sprintf(
                    "srgba(%s, %s, %s, %s)",
                    $color->channel(Red::class)->value(),
                    $color->channel(Green::class)->value(),
                    $color->channel(Blue::class)->value(),
                    $color->channel(Alpha::class)->value(),
                )
            );
        } catch (ImagickException $e) {
            throw new DriverException('Failed to create color', previous: $e);
        }
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
            Hsl::class => Rgb::class::colorFromNormalized([
                $native->getColorValue(Imagick::COLOR_RED),
                $native->getColorValue(Imagick::COLOR_GREEN),
                $native->getColorValue(Imagick::COLOR_BLUE),
                $native->getColorValue(Imagick::COLOR_ALPHA),
            ])->toColorspace(Hsl::class),
            Hsv::class => Rgb::colorFromNormalized([
                $native->getColorValue(Imagick::COLOR_RED),
                $native->getColorValue(Imagick::COLOR_GREEN),
                $native->getColorValue(Imagick::COLOR_BLUE),
                $native->getColorValue(Imagick::COLOR_ALPHA),
            ])->toColorspace(Hsv::class),
            Oklab::class => Rgb::colorFromNormalized([
                $native->getColorValue(Imagick::COLOR_RED),
                $native->getColorValue(Imagick::COLOR_GREEN),
                $native->getColorValue(Imagick::COLOR_BLUE),
                $native->getColorValue(Imagick::COLOR_ALPHA),
            ])->toColorspace(Oklab::class),
            Oklch::class => Rgb::colorFromNormalized([
                $native->getColorValue(Imagick::COLOR_RED),
                $native->getColorValue(Imagick::COLOR_GREEN),
                $native->getColorValue(Imagick::COLOR_BLUE),
                $native->getColorValue(Imagick::COLOR_ALPHA),
            ])->toColorspace(Oklch::class),
            default => throw new NotSupportedException(
                'Colorspace ' . $this->colorspace::class . ' is not supported by driver'
            )
        };
    }
}
