<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use ImagickException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\FillTransparentModifier as GenericFillTransparentModifier;

class FillTransparentModifier extends GenericFillTransparentModifier implements SpecializedInterface
{
    /**
     * @throws ModifierException
     * @throws StateException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $backgroundColor = $this->backgroundColor($this->driver());

        // get imagickpixel from background color
        $pixel = $this->driver()
            ->colorProcessor($image)
            ->colorToNative($backgroundColor);

        // merge transparent areas with the background color
        foreach ($image as $frame) {
            try {
                $frame->native()->setImageBackgroundColor($pixel);
                $frame->native()->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE);
                $frame->native()->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
            } catch (ImagickException $e) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to set image background color',
                    previous: $e
                );
            }
        }

        return $image;
    }
}
