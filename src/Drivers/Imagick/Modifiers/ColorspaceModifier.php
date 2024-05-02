<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\ColorspaceInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Colors\Cmyk\Colorspace as CmykColorspace;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\ColorspaceModifier as GenericColorspaceModifier;

class ColorspaceModifier extends GenericColorspaceModifier implements SpecializedInterface
{
    /**
     * Map own colorspace classname to Imagick classnames
     *
     * @var array<string, int>
     */
    protected static array $mapping = [
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

    /**
     * @throws NotSupportedException
     */
    private function getImagickColorspace(ColorspaceInterface $colorspace): int
    {
        if (!array_key_exists($colorspace::class, self::$mapping)) {
            throw new NotSupportedException('Given colorspace is not supported.');
        }

        return self::$mapping[$colorspace::class];
    }
}
