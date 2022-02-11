<?php

namespace Intervention\Image\Drivers\Abstract;

use Intervention\Image\Collection;
use Intervention\Image\EncodedImage;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Size;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Traits\CanHandleInput;
use Intervention\Image\Traits\CanResolveDriverClass;

abstract class AbstractImage
{
    use CanResolveDriverClass;
    use CanHandleInput;

    public function __construct(protected Collection $frames, protected $loops = 0)
    {
        //
    }

    public function getIterator(): Collection
    {
        return $this->frames;
    }

    public function getFrames(): Collection
    {
        return $this->frames;
    }

    public function getFrame(int $key = 0): ?FrameInterface
    {
        return $this->frames->get($key);
    }

    public function addFrame(FrameInterface $frame): ImageInterface
    {
        $this->frames->push($frame);

        return $this;
    }

    public function setLoops(int $count): ImageInterface
    {
        $this->loops = $count;

        return $this;
    }

    public function getLoops(): int
    {
        return $this->loops;
    }

    public function getSize(): SizeInterface
    {
        return new Size($this->getWidth(), $this->getHeight());
    }

    public function size(): SizeInterface
    {
        return $this->getSize();
    }

    public function isAnimated(): bool
    {
        return $this->getFrames()->count() > 1;
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

    public function pickColors(int $x, int $y): Collection
    {
        $colors = new Collection();
        foreach ($this->getFrames() as $key => $frame) {
            $colors->push($this->pickColor($x, $y, $key));
        }

        return $colors;
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

    public function padDown(int $width, int $height, $background = 'ffffff', string $position = 'center'): ImageInterface
    {
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
