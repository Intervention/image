<?php

namespace Intervention\Image;

use Countable;
use Traversable;
use Intervention\Image\Analyzers\ColorspaceAnalyzer;
use Intervention\Image\Analyzers\HeightAnalyzer;
use Intervention\Image\Analyzers\PixelColorAnalyzer;
use Intervention\Image\Analyzers\PixelColorsAnalyzer;
use Intervention\Image\Analyzers\ProfileAnalyzer;
use Intervention\Image\Analyzers\ResolutionAnalyzer;
use Intervention\Image\Analyzers\WidthAnalyzer;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\AnalyzerInterface;
use Intervention\Image\Interfaces\CollectionInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;
use Intervention\Image\Interfaces\CoreInterface;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\FontInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\ProfileInterface;
use Intervention\Image\Interfaces\ResolutionInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Modifiers\GreyscaleModifier;
use Intervention\Image\Modifiers\PixelateModifier;
use Intervention\Image\Modifiers\SharpenModifier;
use Intervention\Image\Modifiers\TextModifier;
use Intervention\Image\Typography\FontFactory;

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

    public function exif(?string $query = null): mixed
    {
        return is_null($query) ? $this->exif : $this->exif->get($query);
    }

    public function modify(ModifierInterface $modifier): ImageInterface
    {
        return $this->driver->resolve($modifier)->apply($this);
    }

    public function analyze(AnalyzerInterface $analyzer): mixed
    {
        return $this->driver->resolve($analyzer)->analyze($this);
    }

    public function encode(EncoderInterface $encoder): EncodedImage
    {
        return $this->driver->resolve($encoder)->encode($this);
    }

    public function width(): int
    {
        return $this->analyze(new WidthAnalyzer());
    }

    public function height(): int
    {
        return $this->analyze(new HeightAnalyzer());
    }

    public function size(): SizeInterface
    {
        return new Rectangle($this->width(), $this->height());
    }

    public function colorspace(): ColorspaceInterface
    {
        return $this->analyze(new ColorspaceAnalyzer());
    }

    public function resolution(): ResolutionInterface
    {
        return $this->analyze(new ResolutionAnalyzer());
    }

    public function pickColor(int $x, int $y, int $frame_key = 0): ColorInterface
    {
        return $this->analyze(new PixelColorAnalyzer($x, $y, $frame_key));
    }

    public function pickColors(int $x, int $y): CollectionInterface
    {
        return $this->analyze(new PixelColorsAnalyzer($x, $y));
    }

    public function profile(): ProfileInterface
    {
        return $this->analyze(new ProfileAnalyzer());
    }

    public function sharpen(int $amount = 10): ImageInterface
    {
        return $this->modify(new SharpenModifier($amount));
    }

    public function pixelate(int $size): ImageInterface
    {
        return $this->modify(new PixelateModifier($size));
    }

    public function greyscale(): ImageInterface
    {
        return $this->modify(new GreyscaleModifier());
    }

    public function text(string $text, int $x, int $y, callable|FontInterface $font): ImageInterface
    {
        return $this->modify(
            new TextModifier(
                $text,
                new Point($x, $y),
                call_user_func(new FontFactory($font)),
            ),
        );
    }
}
