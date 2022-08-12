<?php

namespace Intervention\Image\Drivers\Abstract;

use Intervention\Image\Collection;
use Intervention\Image\EncodedImage;
use Intervention\Image\Geometry\Circle;
use Intervention\Image\Geometry\Ellipse;
use Intervention\Image\Geometry\Line;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\CollectionInterface;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Traits\CanHandleInput;
use Intervention\Image\Traits\CanResolveDriverClass;
use Intervention\Image\Traits\CanRunCallback;

abstract class AbstractImage implements ImageInterface
{
    use CanResolveDriverClass;
    use CanHandleInput;
    use CanRunCallback;

    public function eachFrame(callable $callback): self
    {
        foreach ($this as $frame) {
            $callback($frame);
        }

        return $this;
    }

    public function getSize(): SizeInterface
    {
        return new Rectangle($this->getWidth(), $this->getHeight());
    }

    public function size(): SizeInterface
    {
        return $this->getSize();
    }

    public function modify(ModifierInterface $modifier): ImageInterface
    {
        return $modifier->apply($this);
    }

    public function encode(EncoderInterface $encoder): EncodedImage
    {
        return $encoder->encode($this);
    }

    public function toJpeg(int $quality = 75): EncodedImage
    {
        return $this->encode(
            $this->resolveDriverClass('Encoders\JpegEncoder', $quality)
        );
    }

    public function toWebp(int $quality = 75): EncodedImage
    {
        return $this->encode(
            $this->resolveDriverClass('Encoders\WebpEncoder', $quality)
        );
    }

    public function toGif(): EncodedImage
    {
        return $this->encode(
            $this->resolveDriverClass('Encoders\GifEncoder')
        );
    }

    public function toPng(): EncodedImage
    {
        return $this->encode(
            $this->resolveDriverClass('Encoders\PngEncoder')
        );
    }

    public function greyscale(): ImageInterface
    {
        return $this->modify(
            $this->resolveDriverClass('Modifiers\GreyscaleModifier')
        );
    }

    public function invert(): ImageInterface
    {
        return $this->modify(
            $this->resolveDriverClass('Modifiers\InvertModifier')
        );
    }

    public function brightness(int $level): ImageInterface
    {
        return $this->modify(
            $this->resolveDriverClass('Modifiers\BrightnessModifier', $level)
        );
    }

    public function contrast(int $level): ImageInterface
    {
        return $this->modify(
            $this->resolveDriverClass('Modifiers\ContrastModifier', $level)
        );
    }

    public function gamma(float $gamma): ImageInterface
    {
        return $this->modify(
            $this->resolveDriverClass('Modifiers\GammaModifier', $gamma)
        );
    }

    public function blur(int $amount = 5): ImageInterface
    {
        return $this->modify(
            $this->resolveDriverClass('Modifiers\BlurModifier', $amount)
        );
    }

    public function rotate(float $angle, $background = 'ffffff'): ImageInterface
    {
        return $this->modify(
            $this->resolveDriverClass('Modifiers\RotateModifier', $angle, $background)
        );
    }

    /**
     * Creates a vertical mirror image
     *
     * @return ImageInterface
     */
    public function flip(): ImageInterface
    {
        return $this->modify(
            $this->resolveDriverClass('Modifiers\FlipModifier')
        );
    }

    /**
     * Creates a horizontal mirror image
     *
     * @return ImageInterface
     */
    public function flop(): ImageInterface
    {
        return $this->modify(
            $this->resolveDriverClass('Modifiers\FlopModifier')
        );
    }

    public function place($element, string $position = 'top-left', int $offset_x = 0, int $offset_y = 0): ImageInterface
    {
        return $this->modify(
            $this->resolveDriverClass(
                'Modifiers\PlaceModifier',
                $element,
                $position,
                $offset_x,
                $offset_y
            )
        );
    }

    public function fill($color, ?int $x = null, ?int $y = null): ImageInterface
    {
        $color = $this->handleInput($color);
        $position = (is_null($x) && is_null($y)) ? null : new Point($x, $y);

        return $this->modify(
            $this->resolveDriverClass(
                'Modifiers\FillModifier',
                $color,
                $position
            )
        );
    }

    public function pixelate(int $size): ImageInterface
    {
        return $this->modify(
            $this->resolveDriverClass('Modifiers\PixelateModifier', $size)
        );
    }

    public function sharpen(int $amount = 10): ImageInterface
    {
        return $this->modify(
            $this->resolveDriverClass('Modifiers\SharpenModifier', $amount)
        );
    }

    public function pickColors(int $x, int $y): CollectionInterface
    {
        $colors = new Collection();
        foreach ($this as $key => $frame) {
            $colors->push($this->pickColor($x, $y, $key));
        }

        return $colors;
    }

    public function text(string $text, int $x, int $y, ?callable $init = null): ImageInterface
    {
        $font = $this->runCallback($init, $this->resolveDriverClass('Font'));

        $modifier = $this->resolveDriverClass('Modifiers\TextWriter', new Point($x, $y), $font, $text);

        return $this->modify($modifier);
    }

    public function drawPixel(int $x, int $y, $color = null): ImageInterface
    {
        return $this->modify(
            $this->resolveDriverClass('Modifiers\DrawPixelModifier', new Point($x, $y), $color)
        );
    }

    public function drawRectangle(int $x, int $y, ?callable $init = null): ImageInterface
    {
        $rectangle = $this->runCallback($init, new Rectangle(0, 0));
        $modifier = $this->resolveDriverClass('Modifiers\DrawRectangleModifier', new Point($x, $y), $rectangle);

        return $this->modify($modifier);
    }

    public function drawEllipse(int $x, int $y, ?callable $init = null): ImageInterface
    {
        $ellipse = $this->runCallback($init, new Ellipse(0, 0));
        $modifier = $this->resolveDriverClass('Modifiers\DrawEllipseModifier', new Point($x, $y), $ellipse);

        return $this->modify($modifier);
    }

    public function drawCircle(int $x, int $y, ?callable $init = null): ImageInterface
    {
        $circle = $this->runCallback($init, new Circle(0));
        $modifier = $this->resolveDriverClass('Modifiers\DrawEllipseModifier', new Point($x, $y), $circle);

        return $this->modify($modifier);
    }

    public function drawLine(callable $init = null): ImageInterface
    {
        $line = $this->runCallback($init, new Line(new Point(), new Point()));
        $modifier = $this->resolveDriverClass('Modifiers\DrawLineModifier', $line->getStart(), $line);

        return $this->modify($modifier);
    }

    public function drawPolygon(callable $init = null): ImageInterface
    {
        $polygon = $this->runCallback($init, new Polygon());
        $modifier = $this->resolveDriverClass('Modifiers\DrawPolygonModifier', $polygon);

        return $this->modify($modifier);
    }

    public function resize(?int $width = null, ?int $height = null): ImageInterface
    {
        return $this->modify(
            $this->resolveDriverClass('Modifiers\ResizeModifier', $width, $height)
        );
    }

    public function resizeDown(?int $width = null, ?int $height = null): ImageInterface
    {
        return $this->modify(
            $this->resolveDriverClass('Modifiers\ResizeDownModifier', $width, $height)
        );
    }

    public function scale(?int $width = null, ?int $height = null): ImageInterface
    {
        return $this->modify(
            $this->resolveDriverClass('Modifiers\ScaleModifier', $width, $height)
        );
    }

    public function scaleDown(?int $width = null, ?int $height = null): ImageInterface
    {
        return $this->modify(
            $this->resolveDriverClass('Modifiers\ScaleDownModifier', $width, $height)
        );
    }

    public function fit(int $width, int $height, string $position = 'center'): ImageInterface
    {
        return $this->modify(
            $this->resolveDriverClass('Modifiers\FitModifier', $width, $height, $position)
        );
    }

    public function fitDown(int $width, int $height, string $position = 'center'): ImageInterface
    {
        return $this->modify(
            $this->resolveDriverClass('Modifiers\FitDownModifier', $width, $height, $position)
        );
    }

    public function pad(int $width, int $height, $background = 'ffffff', string $position = 'center'): ImageInterface
    {
        return $this->modify(
            $this->resolveDriverClass('Modifiers\PadModifier', $width, $height, $background, $position)
        );
    }

    public function padDown(
        int $width,
        int $height,
        $background = 'ffffff',
        string $position = 'center'
    ): ImageInterface {
        return $this->modify(
            $this->resolveDriverClass('Modifiers\PadDownModifier', $width, $height, $background, $position)
        );
    }

    public function destroy(): void
    {
        $this->modify(
            $this->resolveDriverClass('Modifiers\DestroyModifier')
        );
    }
}
