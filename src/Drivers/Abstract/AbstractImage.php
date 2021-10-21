<?php

namespace Intervention\Image\Drivers\Abstract;

use Intervention\Image\Collection;
use Intervention\Image\Exceptions\NotWritableException;
use Intervention\Image\Geometry\Size;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Traits\CanResolveDriverClass;

abstract class AbstractImage
{
    use CanResolveDriverClass;

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

    public function size(): SizeInterface
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

    public function encode(EncoderInterface $encoder, ?string $path = null): string
    {
        $encoded = $encoder->encode($this);

        if ($path) {
            $saved = @file_put_contents($path, $encoded);
            if ($saved === false) {
                throw new NotWritableException(
                    "Can't write image data to path ({$path})."
                );
            }
        }

        return $encoded;
    }

    public function toJpeg(?int $quality = null, ?string $path = null): string
    {
        return $this->encode(
            $this->resolveDriverClass('Encoders\JpegEncoder', $quality),
            $path
        );
    }

    public function toGif(?string $path = null): string
    {
        return $this->encode(
            $this->resolveDriverClass('Encoders\GifEncoder'),
            $path
        );
    }
}
