<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\BackgroundModifier as GenericBackgroundModifier;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\StateException;

class BackgroundModifier extends GenericBackgroundModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     *
     * @throws InvalidArgumentException
     * @throws ModifierException
     * @throws StateException
     * @throws DriverException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $backgroundColor = $this->backgroundColor($this->driver())->toColorspace(RgbColorspace::class);

        if (!($backgroundColor instanceof RgbColor)) {
            throw new ModifierException('Failed to normalize background color to rgb color space');
        }

        foreach ($image as $frame) {
            // create new canvas with background color as background
            $modified = Cloner::cloneBlended(
                $frame->native(),
                background: $backgroundColor
            );

            // set new gd image
            $frame->setNative($modified);
        }

        return $image;
    }
}
