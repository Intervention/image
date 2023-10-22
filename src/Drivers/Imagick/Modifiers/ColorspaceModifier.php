<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use Intervention\Image\Colors\Cmyk\Colorspace as CmykColorspace;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Drivers\Imagick\Image;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\ColorspaceInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Traits\CanCheckType;

class ColorspaceModifier implements ModifierInterface
{
    use CanCheckType;

    protected static $mapping = [
        RgbColorspace::class => Imagick::COLORSPACE_SRGB,
        CmykColorspace::class => Imagick::COLORSPACE_CMYK,
    ];

    public function __construct(protected string|ColorspaceInterface $target)
    {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        $colorspace = $this->targetColorspace();

        $imagick = $this->failIfNotClass($image, Image::class)->getImagick();
        $imagick->transformImageColorspace(
            $this->getImagickColorspace($colorspace)
        );

        return $image;
    }

    private function getImagickColorspace(ColorspaceInterface $colorspace): int
    {
        if (!array_key_exists(get_class($colorspace), self::$mapping)) {
            throw new NotSupportedException('Given colorspace is not supported.');
        }

        return self::$mapping[get_class($colorspace)];
    }

    private function targetColorspace(): ColorspaceInterface
    {
        if (is_object($this->target)) {
            return $this->target;
        }

        if (in_array($this->target, ['rgb', 'RGB', RgbColorspace::class])) {
            return new RgbColorspace();
        }

        if (in_array($this->target, ['cmyk', 'CMYK', CmykColorspace::class])) {
            return new CmykColorspace();
        }

        throw new NotSupportedException('Given colorspace is not supported.');
    }
}
