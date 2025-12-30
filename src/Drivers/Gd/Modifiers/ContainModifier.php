<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Colors\Rgb\Colorspace as Rgb;
use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\ContainModifier as GenericContainModifier;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\StateException;

class ContainModifier extends GenericContainModifier implements SpecializedInterface
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
        $crop = $this->getCropSize($image);
        $resize = $this->getResizeSize($image);
        $backgroundColor = $this->backgroundColor()->toColorspace(Rgb::class);

        if (!($backgroundColor instanceof RgbColor)) {
            throw new ModifierException('Failed to normalize background color to rgb color space');
        }

        foreach ($image as $frame) {
            $this->modify($frame, $crop, $resize, $backgroundColor);
        }

        return $image;
    }

    /**
     * @throws InvalidArgumentException
     * @throws ModifierException
     * @throws DriverException
     */
    private function modify(
        FrameInterface $frame,
        SizeInterface $crop,
        SizeInterface $resize,
        RgbColor $backgroundColor
    ): void {
        // create new gd image
        $modified = Cloner::cloneEmpty($frame->native(), $resize, $backgroundColor);

        // make image area transparent to keep transparency
        // even if background-color is set
        $transparent = imagecolorallocatealpha(
            $modified,
            $backgroundColor->red()->value(),
            $backgroundColor->green()->value(),
            $backgroundColor->blue()->value(),
            127,
        );

        if ($transparent === false) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable allocate transparent color',
            );
        }

        $result = imagealphablending($modified, false); // do not blend / just overwrite

        if ($result === false) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to set alpha blending',
            );
        }

        imagecolortransparent($modified, $transparent);

        $result = imagefilledrectangle(
            $modified,
            $crop->pivot()->x(),
            $crop->pivot()->y(),
            $crop->pivot()->x() + $crop->width() - 1,
            $crop->pivot()->y() + $crop->height() - 1,
            $transparent
        );

        if ($result === false) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable fill image with rectangle',
            );
        }

        // copy image from original with background alpha
        $result = imagealphablending($modified, true);

        if ($result === false) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to set alpha blending',
            );
        }

        $result = imagecopyresampled(
            $modified,
            $frame->native(),
            $crop->pivot()->x(),
            $crop->pivot()->y(),
            0,
            0,
            $crop->width(),
            $crop->height(),
            $frame->size()->width(),
            $frame->size()->height()
        );

        if ($result === false) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to resize image',
            );
        }

        // set new content as resource
        $frame->setNative($modified);
    }
}
