<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use ImagickDrawException;
use ImagickException;
use Intervention\Image\Drivers\Imagick\FontProcessor;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Exceptions\FontException;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\FontInterface;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\PointInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\TextModifier as GenericTextModifier;
use Intervention\Image\Typography\Line;

class TextModifier extends GenericTextModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $lines = $this->processor()->textBlock($this->text, $this->font, $this->position);
        $drawText = $this->imagickDrawText($image, $this->font);
        $drawStroke = $this->imagickDrawStroke($image, $this->font);

        foreach ($image as $frame) {
            foreach ($lines as $line) {
                foreach ($this->strokeOffsets($this->font) as $offset) {
                    // Draw the stroke outline under the actual text
                    $this->maybeDrawTextline($frame, $line, $drawStroke, $offset);
                }

                // Draw the actual text
                $this->maybeDrawTextline($frame, $line, $drawText);
            }
        }

        return $image;
    }

    /**
     * Create an ImagickDraw object to draw text on the image
     *
     * @param ImageInterface $image
     * @param FontInterface $font
     * @throws RuntimeException
     * @throws ColorException
     * @throws FontException
     * @throws ImagickDrawException
     * @throws ImagickException
     * @return ImagickDraw
     */
    private function imagickDrawText(ImageInterface $image, FontInterface $font): ImagickDraw
    {
        $color = $this->driver()->handleInput($font->color());

        if ($font->hasStrokeEffect() && $color->isTransparent()) {
            throw new ColorException(
                'The text color must be fully opaque when using the stroke effect.'
            );
        }

        $color = $this->driver()->colorProcessor($image->colorspace())->colorToNative($color);

        return $this->processor()->toImagickDraw($font, $color);
    }

    /**
     * Create a ImagickDraw object to draw the outline stroke effect on the Image
     *
     * @param ImageInterface $image
     * @param FontInterface $font
     * @throws RuntimeException
     * @throws ColorException
     * @throws FontException
     * @throws ImagickDrawException
     * @throws ImagickException
     * @return null|ImagickDraw
     */
    private function imagickDrawStroke(ImageInterface $image, FontInterface $font): ?ImagickDraw
    {
        if (!$font->hasStrokeEffect()) {
            return null;
        }

        $color = $this->driver()->handleInput($font->strokeColor());

        if ($color->isTransparent()) {
            throw new ColorException(
                'The stroke color must be fully opaque.'
            );
        }

        $color = $this->driver()->colorProcessor($image->colorspace())->colorToNative($color);

        return $this->processor()->toImagickDraw($font, $color);
    }

    /**
     * Maybe draw given line of text on frame instance depending on given
     * ImageDraw instance. Optionally move line position by given offset.
     *
     * @param FrameInterface $frame
     * @param Line $textline
     * @param null|ImagickDraw $draw
     * @param PointInterface $offset
     * @return void
     */
    private function maybeDrawTextline(
        FrameInterface $frame,
        Line $textline,
        ?ImagickDraw $draw = null,
        PointInterface $offset = new Point(),
    ): void {
        if ($draw !== null) {
            $frame->native()->annotateImage(
                $draw,
                $textline->position()->x() + $offset->x(),
                $textline->position()->y() + $offset->y(),
                $this->font->angle(),
                (string) $textline
            );
        }
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
