<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use Intervention\Image\Drivers\DriverSpecialized;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\ColorspaceInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Colors\Cmyk\Colorspace as CmykColorspace;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Interfaces\ModifierInterface;

/**
 * @method ColorspaceInterface targetColorspace()
 */
class ColorspaceModifier extends DriverSpecialized implements ModifierInterface
{
    protected static $mapping = [
        RgbColorspace::class => Imagick::COLORSPACE_SRGB,
        CmykColorspace::class => Imagick::COLORSPACE_CMYK,
    ];

    public function apply(ImageInterface $image): ImageInterface
    {
        $colorspace = $this->targetColorspace();

        $imagick = $image->core()->native();
        $imagick->transformImageColorspace(
            $this->getImagickColorspace($colorspace)
        );

        return $image;
    }

    private function getImagickColorspace(ColorspaceInterface $colorspace): int
    {
        if (!array_key_exists($colorspace::class, self::$mapping)) {
            throw new NotSupportedException('Given colorspace is not supported.');
        }

        return self::$mapping[$colorspace::class];
    }
}
