<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick;

use Imagick;
use ImagickDraw;
use ImagickPixel;
use Intervention\Image\Drivers\AbstractFontProcessor;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\FontInterface;
use Intervention\Image\Interfaces\SizeInterface;

class FontProcessor extends AbstractFontProcessor
{
    /**
     * {@inheritdoc}
     *
     * @see FontProcessorInterface::boxSize()
     */
    public function boxSize(string $text, FontInterface $font): SizeInterface
    {
        // no text - no box size
        if (mb_strlen($text) === 0) {
            return new Rectangle(0, 0);
        }

        $draw = $this->toImagickDraw($font);
        $dimensions = (new Imagick())->queryFontMetrics($draw, $text);

        return new Rectangle(
            intval(round($dimensions['textWidth'])),
            intval(round($dimensions['ascender'] + $dimensions['descender'])),
        );
    }

    /**
     * Imagick::annotateImage() needs an ImagickDraw object - this method takes
     * the font object as the base and adds an optional passed color to the new
     * ImagickDraw object.
     */
    public function toImagickDraw(FontInterface $font, ?ImagickPixel $color = null): ImagickDraw
    {
        if (!$font->hasFile()) {
            throw new StateException('No font file specified');
        }

        $draw = new ImagickDraw();
        $draw->setStrokeAntialias(true);
        $draw->setTextAntialias(true);
        $draw->setFont($font->filepath());
        $draw->setFontSize($this->nativeFontSize($font));
        $draw->setTextAlignment(Imagick::ALIGN_LEFT);

        if ($color instanceof ImagickPixel) {
            $draw->setFillColor($color);
        }

        return $draw;
    }
}
