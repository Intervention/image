<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\DriverSpecialized;
use Intervention\Image\Drivers\Imagick\FontProcessor;
use Intervention\Image\Exceptions\FontException;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\FontInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

/**
 * @property Point $position
 * @property string $text
 * @property FontInterface $font
 */
class TextModifier extends DriverSpecialized implements ModifierInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $fontProcessor = $this->processor();
        $lines = $fontProcessor->textBlock($this->text, $this->font, $this->position);
        $color = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->driver()->handleInput($this->font->color())
        );

        $draw = $fontProcessor->toImagickDraw($this->font, $color);

        foreach ($image as $frame) {
            foreach ($lines as $line) {
                $frame->native()->annotateImage(
                    $draw,
                    $line->position()->x(),
                    $line->position()->y(),
                    $this->font->angle(),
                    (string) $line
                );
            }
        }

        return $image;
    }

    /**
     * Return imagick font processor
     *
     * @return FontProcessor
     */
    private function processor(): FontProcessor
    {
        $processor = $this->driver()->fontProcessor();

        if (!($processor instanceof FontProcessor)) {
            throw new FontException('Font processor does not match the driver.');
        }

        return $processor;
    }
}
