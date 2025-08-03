<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Exceptions\InputException;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\QuantizeColorsModifier as GenericQuantizeColorsModifier;

class QuantizeColorsModifier extends GenericQuantizeColorsModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     *
     * @throws RuntimeException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        if ($this->limit <= 0) {
            throw new InputException('Quantization limit must be greater than 0.');
        }

        // no color reduction if the limit is higher than the colors in the img
        $colorCount = imagecolorstotal($image->core()->native());
        if ($colorCount > 0 && $this->limit > $colorCount) {
            return $image;
        }

        $width = $image->width();
        $height = $image->height();

        $backgroundColor = $this->backgroundColor();
        $nativeBackgroundColor = $this->driver()
            ->colorProcessor($image->colorspace())
            ->colorToNative(
                $backgroundColor
            );

        foreach ($image as $frame) {
            // create new image for color quantization
            $reduced = Cloner::cloneEmpty($frame->native(), background: $backgroundColor);

            // fill with background
            imagefill($reduced, 0, 0, $nativeBackgroundColor);

            // set transparency
            imagecolortransparent($reduced, $nativeBackgroundColor);

            // copy original image (colors are limited automatically in the copy process)
            imagecopy($reduced, $frame->native(), 0, 0, 0, 0, $width, $height);

            // gd library does not support color quantization directly therefore the
            // colors are decrease by transforming the image to a palette version
            imagetruecolortopalette($reduced, true, $this->limit);

            $frame->setNative($reduced);
        }

        return $image;
    }
}
