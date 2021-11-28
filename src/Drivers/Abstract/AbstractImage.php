<?php

namespace Intervention\Image\Drivers\Abstract;

use Intervention\Image\Collection;
use Intervention\Image\EncodedImage;
use Intervention\Image\Exceptions\NotWritableException;
use Intervention\Image\Geometry\Resizer;
use Intervention\Image\Geometry\Size;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Traits\CanResolveDriverClass;

abstract class AbstractImage
{
    use CanResolveDriverClass;

    protected $loops = 0;
    protected $frames;

    public function __construct(Collection $frames)
    {
        $this->frames = $frames;
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

    public function loops(): int
    {
        return $this->loops;
    }

    public function getSize(): SizeInterface
    {
        return new Size($this->width(), $this->height());
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

    public function toJpeg(?int $quality = null): EncodedImage
    {
        return $this->encode(
            $this->resolveDriverClass('Encoders\JpegEncoder', $quality)
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

    public function blur(int $amount): ImageInterface
    {
        return $this->modify(
            $this->resolveDriverClass('Modifiers\BlurModifier', $amount)
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

    public function resize(...$arguments): ImageInterface
    {
        $resized = Resizer::make()->setTargetSizeByArray($arguments)
                ->resize($this->getSize());

        return $this->modify(
            $this->resolveDriverClass('Modifiers\ResizeModifier', $resized)
        );
    }

    public function resizeDown(...$arguments): ImageInterface
    {
        $resized = Resizer::make()->setTargetSizeByArray($arguments)
                ->resizeDown($this->getSize());

        return $this->modify(
            $this->resolveDriverClass('Modifiers\ResizeModifier', $resized)
        );
    }

    public function scale(...$arguments): ImageInterface
    {
        $resized = Resizer::make()->setTargetSizeByArray($arguments)
                ->scale($this->getSize());

        return $this->modify(
            $this->resolveDriverClass('Modifiers\ResizeModifier', $resized)
        );
    }

    public function scaleDown(...$arguments): ImageInterface
    {
        $resized = Resizer::make()->setTargetSizeByArray($arguments)
                ->scaleDown($this->getSize());

        return $this->modify(
            $this->resolveDriverClass('Modifiers\ResizeModifier', $resized)
        );
    }

    public function fit(int $width, int $height, string $position = 'center'): ImageInterface
    {
        // original
        $imagesize = $this->getSize();

        // crop
        $crop = new Size($width, $height);
        $crop = $crop->contain($imagesize)->alignPivotTo($imagesize, $position);

        // resize
        $resize = $crop->scale($width, $height);

        return $this->modify(
            $this->resolveDriverClass('Modifiers\FitModifier', $crop, $resize)
        );
    }

    public function fitDown(int $width, int $height, string $position = 'center'): ImageInterface
    {
        // original
        $imagesize = $this->getSize();

        // crop
        $crop = new Size($width, $height);
        $crop = $crop->contain($imagesize)->alignPivotTo($imagesize, $position);

        // resize
        $resize = $crop->scaleDown($width, $height);

        return $this->modify(
            $this->resolveDriverClass('Modifiers\FitModifier', $crop, $resize)
        );
    }

    public function pad(int $width, int $height, string $position = 'center', $backgroundColor = 'transparent'): ImageInterface
    {
        // original
        $imagesize = $this->getSize();

        $resize = new Size($width, $height);
        $crop = $imagesize->contain($resize)->alignPivotTo($resize, $position);

        return $this->modify(
            $this->resolveDriverClass('Modifiers\PadModifier', $crop, $resize, $backgroundColor)
        );
    }

    public function padDown(int $width, int $height, string $position = 'center', $backgroundColor = 'transparent'): ImageInterface
    {
        // original
        $imagesize = $this->getSize();

        $resize = new Size($width, $height);
        $resize = $resize->resizeDown($imagesize);
        $crop = $imagesize->contain($resize)->alignPivotTo($resize, $position);


        return $this->modify(
            $this->resolveDriverClass('Modifiers\PadModifier', $crop, $resize, $backgroundColor)
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

    public function rotate(float $angle, $backgroundColor = 'ffffff'): ImageInterface
    {
        return $this->modify(
            $this->resolveDriverClass('Modifiers\RotateModifier', $angle, $backgroundColor)
        );
    }
}
