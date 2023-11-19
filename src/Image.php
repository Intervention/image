<?php

namespace Intervention\Image;

use Countable;
use Traversable;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\CollectionInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;
use Intervention\Image\Interfaces\CoreInterface;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;

class Image implements ImageInterface, Countable
{
    public function __construct(
        protected DriverInterface $driver,
        protected CoreInterface $core,
        protected CollectionInterface $exif = new Collection()
    ) {
    }

    public function driver(): DriverInterface
    {
        return $this->driver;
    }

    public function core(): CoreInterface
    {
        return $this->core;
    }

    public function width(): int
    {
        return $this->core->width();
    }

    public function height(): int
    {
        return $this->core->height();
    }

    public function size(): SizeInterface
    {
        return new Rectangle($this->width(), $this->height());
    }

    public function count(): int
    {
        return $this->core->count();
    }

    public function getIterator(): Traversable
    {
        return $this->core;
    }

    public function isAnimated(): bool
    {
        return $this->count() > 1;
    }

    public function loops(): int
    {
        return $this->core->loops();
    }

    public function colorspace(): ColorspaceInterface
    {
        return $this->core->colorspace();
    }

    public function exif(?string $query = null): mixed
    {
        return is_null($query) ? $this->exif : $this->exif->get($query);
    }

    public function modify(ModifierInterface $modifier): ImageInterface
    {
        return $this->driver->resolve($modifier)->apply($this);
    }

    public function encode(EncoderInterface $encoder): EncodedImage
    {
        return $this->driver->resolve($encoder)->encode($this);
    }
}
