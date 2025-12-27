<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\BackgroundModifier as GenericBackgroundModifier;

class BackgroundModifier extends GenericBackgroundModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $backgroundColor = $this->backgroundColor($this->driver())->toColorspace(RgbColorspace::class);

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
