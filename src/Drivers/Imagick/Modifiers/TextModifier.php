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
        $strokeWidth = $this->font->strokeWidth();

        if ($strokeWidth > 0) {
            $strokeColor = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
                $this->driver()->handleInput($this->font->strokeColor())
            );

            if ($strokeWidth > 10) {
                throw new FontException('Stroke width cannot be thicker than 10, please pick lower number.');
            }

            $drawStroke = $fontProcessor->toImagickDraw($this->font, $strokeColor);
        }

        foreach ($image as $frame) {
            foreach ($lines as $line) {
                if ($strokeWidth > 0) {
                    for ($x = -$strokeWidth; $x <= $strokeWidth; $x++) {
                        for ($y = -$strokeWidth; $y <= $strokeWidth; $y++) {
                            $frame->native()->annotateImage(
                                $drawStroke,
                                $line->position()->x() + $x,
                                $line->position()->y() + $y,
                                $this->font->angle(),
                                (string) $line
                            );
                        }
                    }
                }

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
     * @throws FontException
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
