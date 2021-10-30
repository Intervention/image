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

    public function getResizer(): Resizer
    {
        return new Resizer($this->getSize());
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

    public function resize(...$arguments): ImageInterface
    {
        $size = $this->getResizer()->setTargetSizeByArray($arguments)->resize();

        return $this->modify(
            $this->resolveDriverClass('Modifiers\ResizeModifier', $size)
        );
    }

    public function resizeDown(...$arguments): ImageInterface
    {
        $size = $this->getResizer()->setTargetSizeByArray($arguments)->resizeDown();

        return $this->modify(
            $this->resolveDriverClass('Modifiers\ResizeModifier', $size)
        );
    }

    public function scale(...$arguments): ImageInterface
    {
        $size = $this->getResizer()->setTargetSizeByArray($arguments)->scale();

        return $this->modify(
            $this->resolveDriverClass('Modifiers\ResizeModifier', $size)
        );
    }

    public function scaleDown(...$arguments): ImageInterface
    {
        $size = $this->getResizer()->setTargetSizeByArray($arguments)->scaleDown();

        return $this->modify(
            $this->resolveDriverClass('Modifiers\ResizeModifier', $size)
        );
    }

    public function fill($filling): ImageInterface
    {
        return $this->modify(
            $this->resolveDriverClass('Modifiers\FillModifier', $filling)
        );
    }
}
