<?php

namespace Intervention\Image\Drivers\Gd;

use GdImage;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Image;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

class Frame implements FrameInterface
{
    public function __construct(
        protected GdImage $native,
        protected float $delay = 0,
        protected int $dispose = 1,
        protected int $offset_left = 0,
        protected int $offset_top = 0
    ) {
        //
    }

    public function toImage(DriverInterface $driver): ImageInterface
    {
        return new Image($driver, new Core([$this]));
    }

    public function setNative($native): FrameInterface
    {
        $this->native = $native;

        return $this;
    }

    public function native(): GdImage
    {
        return $this->native;
    }

    public function size(): SizeInterface
    {
        return new Rectangle(imagesx($this->native), imagesy($this->native));
    }

    public function delay(): float
    {
        return $this->delay;
    }

    public function setDelay(float $delay): FrameInterface
    {
        $this->delay = $delay;

        return $this;
    }

    public function dispose(): int
    {
        return $this->dispose;
    }

    public function setDispose(int $dispose): FrameInterface
    {
        $this->dispose = $dispose;

        return $this;
    }

    public function setOffset(int $left, int $top): FrameInterface
    {
        $this->offset_left = $left;
        $this->offset_top = $top;

        return $this;
    }

    public function offsetLeft(): int
    {
        return $this->offset_left;
    }

    public function setOffsetLeft(int $offset): FrameInterface
    {
        $this->offset_left = $offset;

        return $this;
    }

    public function offsetTop(): int
    {
        return $this->offset_top;
    }

    public function setOffsetTop(int $offset): FrameInterface
    {
        $this->offset_top = $offset;

        return $this;
    }

    /**
     * This workaround helps cloning GdImages which is currently not possible.
     *
     * @return void
     */
    public function __clone(): void
    {
        // create new clone image
        $width = imagesx($this->native);
        $height = imagesy($this->native);
        $clone = match (imageistruecolor($this->native)) {
            true => imagecreatetruecolor($width, $height),
            default => imagecreate($width, $height),
        };

        // transfer resolution to clone
        $resolution = imageresolution($this->native);
        if (is_array($resolution) && array_key_exists(0, $resolution) && array_key_exists(1, $resolution)) {
            imageresolution($clone, $resolution[0], $resolution[1]);
        }

        // transfer transparency to clone
        $transIndex = imagecolortransparent($this->native);
        if ($transIndex != -1) {
            $rgba = imagecolorsforindex($clone, $transIndex);
            $transColor = imagecolorallocatealpha($clone, $rgba['red'], $rgba['green'], $rgba['blue'], 127);
            imagefill($clone, 0, 0, $transColor);
            imagecolortransparent($clone, $transColor);
        } else {
            imagealphablending($clone, false);
            imagesavealpha($clone, true);
        }

        // transfer actual image to clone
        imagecopy($clone, $this->native, 0, 0, 0, 0, $width, $height);

        $this->native = $clone;
    }
}
