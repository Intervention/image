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
use TypeError;

class ColorspaceModifier extends SpecializableModifier
{
    public function __construct(public string|ColorspaceInterface $target)
    {
        //
    }

    /**
     * Build target color space
     *
     * @throws NotSupportedException
     */
    protected function targetColorspace(): ColorspaceInterface
    {
        if ($this->target instanceof ColorspaceInterface) {
            return $this->target;
        }

        if (class_exists($this->target)) {
            try {
                return new $this->target();
            } catch (TypeError) {
                throw new NotSupportedException(
                    'Target colorspace "' . $this->target . '" is not supported by driver'
                );
            }
        }

        return match (strtolower($this->target)) {
            'rgb', 'srgb', 'rgba', 'srgba' => new Rgb(),
            'cmyk' => new Cmyk(),
            'hsl' => new Hsl(),
            'hsv', 'hsb' => new Hsv(),
            'oklab' => new Oklab(),
            'oklch' => new Oklch(),
            default => throw new NotSupportedException(
                'Colorspace is not supported by driver',
            ),
        };
    }
}
