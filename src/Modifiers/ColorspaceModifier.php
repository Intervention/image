<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Colors\Cmyk\Colorspace as Cmyk;
use Intervention\Image\Colors\Hsl\Colorspace as Hsl;
use Intervention\Image\Colors\Hsv\Colorspace as Hsv;
use Intervention\Image\Colors\Oklab\Colorspace as Oklab;
use Intervention\Image\Colors\Oklch\Colorspace as Oklch;
use Intervention\Image\Colors\Rgb\Colorspace as Rgb;
use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\ColorspaceInterface;

class ColorspaceModifier extends SpecializableModifier
{
    public function __construct(public string|ColorspaceInterface $target)
    {
        //
    }

    public function targetColorspace(): ColorspaceInterface
    {
        if (is_object($this->target)) {
            return $this->target;
        }

        if (in_array($this->target, ['rgb', 'RGB', Rgb::class])) {
            return new Rgb();
        }

        if (in_array($this->target, ['cmyk', 'CMYK', Cmyk::class])) {
            return new Cmyk();
        }

        if (in_array($this->target, ['hsl', 'HSL', Hsl::class])) {
            return new Hsl();
        }

        if (in_array($this->target, ['hsv', 'HSV', 'hsb', 'HSB', Hsv::class])) {
            return new Hsv();
        }

        if (in_array($this->target, ['oklab', 'OKLAB', Oklab::class])) {
            return new Oklab();
        }

        if (in_array($this->target, ['oklch', 'OKLCH', Oklch::class])) {
            return new Oklch();
        }

        throw new NotSupportedException('Colorspace is not supported by driver');
    }
}
