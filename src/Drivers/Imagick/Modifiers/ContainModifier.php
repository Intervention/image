<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use ImagickException;
use ImagickPixel;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\ContainModifier as GenericContainModifier;

class ContainModifier extends GenericContainModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $crop = $this->getCropSize($image);
        $resize = $this->getResizeSize($image);
        $transparent = new ImagickPixel('transparent');

        $background = $this->driver()
            ->colorProcessor($image->colorspace())
            ->colorToNative(
                $this->backgroundColor()
            );

        foreach ($image as $frame) {
            try {
                $result = $frame->native()->scaleImage(
                    $crop->width(),
                    $crop->height(),
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

            try {
                $result = $frame->native()->setBackgroundColor($transparent)
                    && $frame->native()->setImageBackgroundColor($transparent);
                if ($result === false) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to set image background color',
                    );
                }
            } catch (ImagickException $e) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to set image background color',
                    previous: $e
                );
            }

            try {
                $result = $frame->native()->extentImage(
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

            if ($resize->width() > $crop->width()) {
                // fill new emerged background
                $draw = new ImagickDraw();
                $draw->setFillColor($background);

                $delta = abs($crop->pivot()->x());

                if ($delta > 0) {
                    $draw->rectangle(
                        0,
                        0,
                        $delta - 1,
                        $resize->height()
                    );
                }

                $draw->rectangle(
                    $crop->width() + $delta,
                    0,
                    $resize->width(),
                    $resize->height()
                );

                try {
                    $result = $frame->native()->drawImage($draw);
                    if ($result === false) {
                        throw new ModifierException(
                            'Failed to apply ' . self::class . ', unable fill new image areas with replacement color',
                        );
                    }
                } catch (ImagickException $e) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable fill new image areas with replacement color',
                        previous: $e
                    );
                }
            }

            if ($resize->height() > $crop->height()) {
                // fill new emerged background
                $draw = new ImagickDraw();
                $draw->setFillColor($background);

                $delta = abs($crop->pivot()->y());

                if ($delta > 0) {
                    $draw->rectangle(
                        0,
                        0,
                        $resize->width(),
                        $delta - 1
                    );
                }

                $draw->rectangle(
                    0,
                    $crop->height() + $delta,
                    $resize->width(),
                    $resize->height()
                );

                try {
                    $result = $frame->native()->drawImage($draw);
                    if ($result === false) {
                        throw new ModifierException(
                            'Failed to apply ' . self::class . ', unable fill new image areas with replacement color',
                        );
                    }
                } catch (ImagickException $e) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable fill new image areas with replacement color',
                        previous: $e
                    );
                }
            }
        }

        return $image;
    }
}
