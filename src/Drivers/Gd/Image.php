<?php

namespace Intervention\Image\Drivers\Gd;

use Intervention\Image\Collection;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Drivers\Abstract\AbstractImage;
use Intervention\Image\Drivers\Gd\Traits\CanHandleColors;
use Intervention\Image\Exceptions\AnimationException;
use Intervention\Image\Exceptions\GeometryException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ProfileInterface;
use Intervention\Image\Interfaces\ResolutionInterface;
use Intervention\Image\Resolution;
use IteratorAggregate;
use Traversable;

class Image extends AbstractImage implements ImageInterface, IteratorAggregate
{
    use CanHandleColors;

    public function __construct(protected Collection $frames, protected int $loops = 0)
    {
        //
    }

    public function frames(): Collection
    {
        return $this->frames;
    }

    public function setFrames(Collection $frames): ImageInterface
    {
        $this->frames = $frames;

        return $this;
    }

    public function getIterator(): Traversable
    {
        return $this->frames;
    }

    public function count(): int
    {
        return $this->frames->count();
    }

    public function isAnimated(): bool
    {
        return $this->count() > 1;
    }

    public function loops(): int
    {
        return $this->loops;
    }

    public function setLoops(int $count): self
    {
        $this->loops = $count;

        return $this;
    }

    public function frame(int $position = 0): FrameInterface
    {
        if ($frame = $this->frames->get($position)) {
            return $frame;
        }

        throw new AnimationException('Frame #' . $position . ' is not be found in the image.');
    }

    public function addFrame(FrameInterface $frame): ImageInterface
    {
        $this->frames->push($frame);

        return $this;
    }

    public function width(): int
    {
        return imagesx($this->frame()->core());
    }

    public function height(): int
    {
        return imagesy($this->frame()->core());
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::resolution()
     */
    public function resolution(): ResolutionInterface
    {
        return new Resolution(...imageresolution($this->frame()->core()));
    }

    public function pickColor(int $x, int $y, int $frame_key = 0): ColorInterface
    {
        $gd = $this->frame($frame_key)->core();
        $index = @imagecolorat($gd, $x, $y);

        if ($index === false) {
            throw new GeometryException('The specified position is not in the valid image area.');
        }

        $colors = imagecolorsforindex($gd, $index);

        return $this->arrayToColor($colors);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::getColorspace()
     */
    public function colorspace(): ColorspaceInterface
    {
        return new RgbColorspace();
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::setColorspace()
     */
    public function setColorspace(string|ColorspaceInterface $target): ImageInterface
    {
        if (is_string($target) && !in_array($target, ['rgb', RgbColorspace::class])) {
            throw new NotSupportedException('Only RGB colorspace is supported with GD driver.');
        }

        if (is_object($target) && !is_a($target, RgbColorspace::class)) {
            throw new NotSupportedException('Only RGB colorspace is supported with GD driver.');
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::setProfile()
     */
    public function setProfile(string|ProfileInterface $input): ImageInterface
    {
        throw new NotSupportedException('Color profiles are not supported by GD driver.');
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::profile()
     */
    public function profile(): ProfileInterface
    {
        throw new NotSupportedException('Color profiles are not supported by GD driver.');
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::removeProfile()
     */
    public function removeProfile(): ImageInterface
    {
        throw new NotSupportedException('Color profiles are not supported by GD driver.');
    }
}
