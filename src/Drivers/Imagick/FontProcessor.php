<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick;

use Imagick;
use ImagickDraw;
use ImagickDrawException;
use ImagickException;
use ImagickPixel;
use Intervention\Image\Drivers\AbstractFontProcessor;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\FontInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Size;

class FontProcessor extends AbstractFontProcessor
{
    /**
     * ImagickDraw objects with the font file already set, by real font file path
     *
     * @var array<string, ImagickDraw>
     */
    private array $prototypes = [];

    /**
     * {@inheritdoc}
     *
     * @see FontProcessorInterface::boxSize()
     *
     * @throws InvalidArgumentException
     * @throws StateException
     * @throws DriverException
     */
    public function boxSize(string $text, FontInterface $font): SizeInterface
    {
        // no text - no box size
        if (mb_strlen($text) === 0) {
            return new Size(0, 0);
        }

        $draw = $this->toImagickDraw($font);
        try {
            $dimensions = (new Imagick())->queryFontMetrics($draw, $text);
        } catch (ImagickException $e) {
            throw new DriverException('Failed query font metrics', previous: $e);
        }

        return new Size(
            intval(round($dimensions['textWidth'])),
            intval(round($dimensions['ascender'] + $dimensions['descender'])),
        );
    }

    /**
     * Imagick::annotateImage() needs an ImagickDraw object - this method takes
     * the font object as the base and adds an optional passed color to the new
     * ImagickDraw object.
     *
     * @throws StateException
     * @throws DriverException
     */
    public function toImagickDraw(FontInterface $font, ?ImagickPixel $color = null): ImagickDraw
    {
        if (!$font->hasFile()) {
            throw new StateException('No font file specified');
        }

        try {
            // ImagickDraw::setFont() leaks memory in each call (imagick 3.8.1 and earlier, see
            // Imagick/imagick#786), so the font is set only once per file and then cloned
            $key = realpath($font->filepath()) ?: $font->filepath();
            if (!isset($this->prototypes[$key])) {
                $prototype = new ImagickDraw();
                $prototype->setStrokeAntialias(true);
                $prototype->setTextAntialias(true);
                $prototype->setFont($font->filepath());
                $prototype->setTextAlignment(Imagick::ALIGN_LEFT);
                $this->prototypes[$key] = $prototype;
            }

            $draw = clone $this->prototypes[$key];
            $draw->setFontSize($this->nativeFontSize($font));

            if ($color instanceof ImagickPixel) {
                $draw->setFillColor($color);
            }
        } catch (ImagickException | ImagickDrawException $e) {
            throw new DriverException('Failed to convert font to ImagickDraw instance', previous: $e);
        }

        return $draw;
    }
}
