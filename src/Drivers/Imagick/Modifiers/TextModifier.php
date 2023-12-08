<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use ImagickDraw;
use ImagickPixel;
use Intervention\Image\Drivers\AbstractTextModifier;
use Intervention\Image\Exceptions\FontException;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\FontInterface;
use Intervention\Image\Interfaces\ImageInterface;

/**
 * @property Point $position
 * @property string $text
 * @property FontInterface $font
 */
class TextModifier extends AbstractTextModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $lines = $this->alignedTextBlock($this->position, $this->text);

        $color = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->driver()->handleInput($this->font->color())
        );

        $draw = $this->toImagickDraw($color);

        foreach ($image as $frame) {
            foreach ($lines as $line) {
                $frame->native()->annotateImage(
                    $draw,
                    $line->position()->x(),
                    $line->position()->y(),
                    $this->font->angle(),
                    $line
                );
            }
        }

        return $image;
    }

    /**
     * Calculate box size of current font
     *
     * @return Polygon
     */
    protected function boxSize(string $text): Polygon
    {
        // no text - no box size
        if (mb_strlen($text) === 0) {
            return (new Rectangle(0, 0));
        }

        $draw = $this->toImagickDraw();
        $draw->setStrokeAntialias(true);
        $draw->setTextAntialias(true);
        $dimensions = (new Imagick())->queryFontMetrics($draw, $text);

        return (new Rectangle(
            intval(round($dimensions['textWidth'])),
            intval(round($dimensions['ascender'] + $dimensions['descender'])),
        ));
    }

    private function toImagickDraw(?ImagickPixel $color = null): ImagickDraw
    {
        if (!$this->font->hasFilename()) {
            throw new FontException('No font file specified.');
        }

        $draw = new ImagickDraw();
        $draw->setStrokeAntialias(true);
        $draw->setTextAntialias(true);
        $draw->setFont($this->font->filename());
        $draw->setFontSize($this->font->size());
        $draw->setTextAlignment(Imagick::ALIGN_LEFT);

        if ($color) {
            $draw->setFillColor($color);
        }

        return $draw;
    }
}
