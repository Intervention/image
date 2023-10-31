<?php

namespace Intervention\Image\Drivers\Imagick;

use Imagick;
use ImagickException;
use Intervention\Image\Colors\Cmyk\Colorspace as CmykColorspace;
use Intervention\Image\Colors\Profile;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Drivers\Abstract\AbstractImage;
use Intervention\Image\Drivers\Imagick\Modifiers\ColorspaceModifier;
use Intervention\Image\Drivers\Imagick\Modifiers\ProfileModifier;
use Intervention\Image\Drivers\Imagick\Modifiers\ProfileRemovalModifier;
use Intervention\Image\Drivers\Imagick\Traits\CanHandleColors;
use Intervention\Image\Exceptions\AnimationException;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ProfileInterface;
use Intervention\Image\Interfaces\ResolutionInterface;
use Intervention\Image\Resolution;
use Iterator;

class Image extends AbstractImage implements ImageInterface, Iterator
{
    use CanHandleColors;

    protected $iteratorIndex = 0;

    public function __construct(protected Imagick $imagick)
    {
        //
    }

    public function getImagick(): Imagick
    {
        return $this->imagick;
    }

    public function setImagick(Imagick $imagick): ImageInterface
    {
        $this->imagick = $imagick;

        return $this;
    }

    public function frame(int $position = 0): FrameInterface
    {
        foreach ($this->imagick as $core) {
            if ($core->getIteratorIndex() == $position) {
                return new Frame($core);
            }
        }

        throw new AnimationException('Frame #' . $position . ' is not be found in the image.');
    }

    public function addFrame(FrameInterface $frame): ImageInterface
    {
        $imagick = $frame->core();

        $imagick->setImageDelay($frame->delay());
        $imagick->setImageDispose($frame->dispose());

        $size = $frame->size();
        $imagick->setImagePage(
            $size->width(),
            $size->height(),
            $frame->offsetLeft(),
            $frame->offsetTop()
        );

        $this->imagick->addImage($imagick);

        return $this;
    }

    public function setLoops(int $count): ImageInterface
    {
        $this->imagick = $this->imagick->coalesceImages();
        $this->imagick->setImageIterations($count);

        return $this;
    }

    public function loops(): int
    {
        return $this->imagick->getImageIterations();
    }

    public function isAnimated(): bool
    {
        return $this->count() > 1;
    }

    public function count(): int
    {
        return $this->imagick->getNumberImages();
    }

    public function current(): mixed
    {
        $this->imagick->setIteratorIndex($this->iteratorIndex);

        return new Frame($this->imagick->current());
    }

    public function key(): mixed
    {
        return $this->iteratorIndex;
    }

    public function next(): void
    {
        $this->iteratorIndex = $this->iteratorIndex + 1;
    }

    public function rewind(): void
    {
        $this->iteratorIndex = 0;
    }

    public function valid(): bool
    {
        try {
            $result = $this->imagick->setIteratorIndex($this->iteratorIndex);
        } catch (ImagickException $e) {
            return false;
        }

        return $result;
    }

    public function width(): int
    {
        return $this->frame()->core()->getImageWidth();
    }

    public function height(): int
    {
        return $this->frame()->core()->getImageHeight();
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::resolution()
     */
    public function resolution(): ResolutionInterface
    {
        return new Resolution(...$this->frame()->core()->getImageResolution());
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::pickColor()
     */
    public function pickColor(int $x, int $y, int $frame_key = 0): ColorInterface
    {
        return $this->pixelToColor(
            $this->frame($frame_key)->core()->getImagePixelColor($x, $y),
            $this->colorspace()
        );
    }

    public function colorspace(): ColorspaceInterface
    {
        return match ($this->imagick->getImageColorspace()) {
            Imagick::COLORSPACE_CMYK => new CmykColorspace(),
            default => new RgbColorspace(),
        };
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::setColorspace()
     */
    public function setColorspace(string|ColorspaceInterface $colorspace): ImageInterface
    {
        return $this->modify(new ColorspaceModifier($colorspace));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::setProfile()
     */
    public function setProfile(string|ProfileInterface $input): ImageInterface
    {
        $profile = is_object($input) ? $input : new Profile(file_get_contents($input));

        return $this->modify(new ProfileModifier($profile));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::profile()
     */
    public function profile(): ProfileInterface
    {
        $profiles = $this->imagick->getImageProfiles('icc');

        if (!array_key_exists('icc', $profiles)) {
            throw new ColorException('No ICC profile found.');
        }

        return new Profile($profiles['icc']);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::removeProfile()
     */
    public function removeProfile(): ImageInterface
    {
        return $this->modify(new ProfileRemovalModifier());
    }
}
