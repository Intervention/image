<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\QuantizeColorsModifier as GenericQuantizeColorsModifier;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Exceptions\DriverException;

class QuantizeColorsModifier extends GenericQuantizeColorsModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     *
     * @throws InvalidArgumentException
     * @throws StateException
     * @throws ModifierException
     * @throws DriverException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        if ($this->limit <= 0) {
            throw new InvalidArgumentException('Quantization limit must be greater than 0');
        }

        // no color reduction if the limit is higher than the colors in the img
        $colorCount = imagecolorstotal($image->core()->native());
        if ($colorCount > 0 && $this->limit > $colorCount) {
            return $image;
        }

        $width = $image->width();
        $height = $image->height();
        $backgroundColor = $this->backgroundColor($image);

        if (!$backgroundColor instanceof RgbColor) {
            throw new ModifierException('Failed to convert background color to RGB color space');
        }

        $nativeBackgroundColor = $this->driver()
            ->colorProcessor($image)
            ->colorToNative($backgroundColor);

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
