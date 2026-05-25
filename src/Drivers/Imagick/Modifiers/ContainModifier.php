<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use ImagickDraw;
use ImagickDrawException;
use ImagickException;
use ImagickPixel;
use ImagickPixelException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\ContainModifier as GenericContainModifier;

class ContainModifier extends GenericContainModifier implements SpecializedInterface
{
    /**
     * @throws InvalidArgumentException
     * @throws ModifierException
     * @throws StateException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $crop = $this->cropSize($image);
        $resize = $this->resizeSize($image);
        $background = $this->driver()->colorProcessor($image)->export($this->backgroundColor());

        foreach ($image as $frame) {
            $this->scaleFrame($frame->native(), $crop->width(), $crop->height());
            $this->setFrameBackgroundTransparent($frame->native());
            $this->extendFrame($frame->native(), $resize, $crop);

            if ($resize->width() > $crop->width()) {
                $this->fillHorizontalAreas($frame->native(), $crop, $resize, $background);
            }

            if ($resize->height() > $crop->height()) {
                $this->fillVerticalAreas($frame->native(), $crop, $resize, $background);
            }
        }

        return $image;
    }

    /**
     * Scale a native Imagick frame to the given dimensions
     *
     * @throws ModifierException
     */
    private function scaleFrame(mixed $native, int $width, int $height): void
    {
        try {
            $result = $native->scaleImage($width, $height);
            if ($result === false) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to resize image',
                );
            }
        } catch (ImagickException $e) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to resize image',
                previous: $e
            );
        }
    }

    /**
     * Set the background and image background color of a frame to transparent.
     *
     * @throws ModifierException
     */
    private function setFrameBackgroundTransparent(Imagick $native): void
    {
        try {
            $transparent = new ImagickPixel('transparent');
            $result = $native->setBackgroundColor($transparent) && $native->setImageBackgroundColor($transparent);
            if ($result === false) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to set image background color',
                );
            }
        } catch (ImagickException | ImagickPixelException $e) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to set image background color',
                previous: $e
            );
        }
    }

    /**
     * Extend the canvas of a frame to the resize dimensions using the crop pivot as offset.
     *
     * @throws ModifierException
     */
    private function extendFrame(Imagick $native, SizeInterface $resize, SizeInterface $crop): void
    {
        try {
            $result = $native->extentImage(
                $resize->width(),
                $resize->height(),
                $crop->pivot()->x() * -1,
                $crop->pivot()->y() * -1
            );
            if ($result === false) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to resize image',
                );
            }
        } catch (ImagickException $e) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to resize image',
                previous: $e
            );
        }
    }

    /**
     * Fill the left and right background areas that emerged after horizontal extension.
     *
     * @throws ModifierException
     */
    private function fillHorizontalAreas(
        Imagick $native,
        SizeInterface $crop,
        SizeInterface $resize,
        ImagickPixel $background,
    ): void {
        try {
            $draw = new ImagickDraw();
            $draw->setFillColor($background);

            $delta = abs($crop->pivot()->x());

            if ($delta > 0) {
                $draw->rectangle(0, 0, $delta - 1, $resize->height());
            }

            $draw->rectangle($crop->width() + $delta, 0, $resize->width(), $resize->height());

            $result = $native->drawImage($draw);

            if ($result === false) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable fill new image areas with replacement color',
                );
            }
        } catch (ImagickException | ImagickDrawException $e) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable fill new image areas with replacement color',
                previous: $e
            );
        }
    }

    /**
     * Fill the top and bottom background areas that emerged after vertical extension.
     *
     * @throws ModifierException
     */
    private function fillVerticalAreas(
        Imagick $native,
        SizeInterface $crop,
        SizeInterface $resize,
        ImagickPixel $background,
    ): void {
        try {
            $draw = new ImagickDraw();
            $draw->setFillColor($background);

            $delta = abs($crop->pivot()->y());

            if ($delta > 0) {
                $draw->rectangle(0, 0, $resize->width(), $delta - 1);
            }

            $draw->rectangle(0, $crop->height() + $delta, $resize->width(), $resize->height());

            $result = $native->drawImage($draw);

            if ($result === false) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable fill new image areas with replacement color',
                );
            }
        } catch (ImagickException | ImagickDrawException $e) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable fill new image areas with replacement color',
                previous: $e
            );
        }
    }
}
