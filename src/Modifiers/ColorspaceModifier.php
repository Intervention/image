<?php

namespace Intervention\Image\Modifiers;

use Intervention\Image\Interfaces\ColorspaceInterface;
use Intervention\Image\Colors\Cmyk\Colorspace as CmykColorspace;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Exceptions\NotSupportedException;

class ColorspaceModifier extends AbstractModifier
{
    public function __construct(public string|ColorspaceInterface $target)
    {
    }

    public function targetColorspace(): ColorspaceInterface
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
