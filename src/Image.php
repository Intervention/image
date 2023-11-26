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
use Intervention\Image\Encoders\AvifEncoder;
use Intervention\Image\Encoders\BmpEncoder;
use Intervention\Image\Encoders\GifEncoder;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\AnalyzerInterface;
use Intervention\Image\Interfaces\CollectionInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;
use Intervention\Image\Interfaces\CoreInterface;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\FontInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\ProfileInterface;
use Intervention\Image\Interfaces\ResolutionInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Modifiers\BlurModifier;
use Intervention\Image\Modifiers\BrightnessModifier;
use Intervention\Image\Modifiers\ColorizeModifier;
use Intervention\Image\Modifiers\ColorspaceModifier;
use Intervention\Image\Modifiers\ContrastModifier;
use Intervention\Image\Modifiers\CropModifier;
use Intervention\Image\Modifiers\FitDownModifier;
use Intervention\Image\Modifiers\FitModifier;
use Intervention\Image\Modifiers\FlipModifier;
use Intervention\Image\Modifiers\FlopModifier;
use Intervention\Image\Modifiers\GammaModifier;
use Intervention\Image\Modifiers\GreyscaleModifier;
use Intervention\Image\Modifiers\InvertModifier;
use Intervention\Image\Modifiers\PadModifier;
use Intervention\Image\Modifiers\PixelateModifier;
use Intervention\Image\Modifiers\PlaceModifier;
use Intervention\Image\Modifiers\ProfileModifier;
use Intervention\Image\Modifiers\RemoveAnimationModifier;
use Intervention\Image\Modifiers\ResizeDownModifier;
use Intervention\Image\Modifiers\ResizeModifier;
use Intervention\Image\Modifiers\ResolutionModifier;
use Intervention\Image\Modifiers\RotateModifier;
use Intervention\Image\Modifiers\ScaleDownModifier;
use Intervention\Image\Modifiers\ScaleModifier;
use Intervention\Image\Modifiers\SharpenModifier;
use Intervention\Image\Modifiers\TextModifier;
use Intervention\Image\Typography\FontFactory;

final class Image implements ImageInterface, Countable
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

    public function removeAnimation(int|string $position = 0): ImageInterface
    {
        return $this->modify(new RemoveAnimationModifier($position));
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

    public function setColorspace(string|ColorspaceInterface $colorspace): ImageInterface
    {
        return $this->modify(new ColorspaceModifier($colorspace));
    }

    public function resolution(): ResolutionInterface
    {
        return $this->analyze(new ResolutionAnalyzer());
    }

    public function setResolution(float $x, float $y): ImageInterface
    {
        return $this->modify(new ResolutionModifier($x, $y));
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

    public function setProfile(ProfileInterface $profile): ImageInterface
    {
        return $this->modify(new ProfileModifier($profile));
    }

    public function sharpen(int $amount = 10): ImageInterface
    {
        return $this->modify(new SharpenModifier($amount));
    }

    public function invert(): ImageInterface
    {
        return $this->modify(new InvertModifier());
    }

    public function pixelate(int $size): ImageInterface
    {
        return $this->modify(new PixelateModifier($size));
    }

    public function greyscale(): ImageInterface
    {
        return $this->modify(new GreyscaleModifier());
    }

    public function brightness(int $level): ImageInterface
    {
        return $this->modify(new BrightnessModifier($level));
    }

    public function contrast(int $level): ImageInterface
    {
        return $this->modify(new ContrastModifier($level));
    }

    public function gamma(float $gamma): ImageInterface
    {
        return $this->modify(new GammaModifier($gamma));
    }

    public function colorize(int $red = 0, int $green = 0, int $blue = 0): ImageInterface
    {
        return $this->modify(new ColorizeModifier($red, $green, $blue));
    }

    public function flip(): ImageInterface
    {
        return $this->modify(new FlipModifier());
    }

    public function flop(): ImageInterface
    {
        return $this->modify(new FlopModifier());
    }

    public function blur(int $amount = 5): ImageInterface
    {
        return $this->modify(new BlurModifier($amount));
    }

    public function rotate(float $angle, mixed $background = 'ffffff'): ImageInterface
    {
        return $this->modify(new RotateModifier($angle, $background));
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

    public function toJpeg(int $quality = 75): EncodedImageInterface
    {
        return $this->encode(new JpegEncoder($quality));
    }

    public function resize(?int $width, ?int $height): ImageInterface
    {
        return $this->modify(new ResizeModifier($width, $height));
    }

    public function resizeDown(?int $width, ?int $height): ImageInterface
    {
        return $this->modify(new ResizeDownModifier($width, $height));
    }

    public function scale(?int $width, ?int $height): ImageInterface
    {
        return $this->modify(new ScaleModifier($width, $height));
    }

    public function scaleDown(?int $width, ?int $height): ImageInterface
    {
        return $this->modify(new ScaleDownModifier($width, $height));
    }

    public function fit(int $width, int $height, string $position = 'center'): ImageInterface
    {
        return $this->modify(new FitModifier($width, $height, $position));
    }

    public function fitDown(int $width, int $height, string $position = 'center'): ImageInterface
    {
        return $this->modify(new FitDownModifier($width, $height, $position));
    }

    public function pad(
        int $width,
        int $height,
        mixed $background = 'ffffff',
        string $position = 'center'
    ): ImageInterface {
        return $this->modify(new PadModifier($width, $height, $background, $position));
    }

    public function padDown(
        int $width,
        int $height,
        mixed $background = 'ffffff',
        string $position = 'center'
    ): ImageInterface {
        return $this->modify(new PadModifier($width, $height, $background, $position));
    }

    public function crop(
        int $width,
        int $height,
        int $offset_x = 0,
        int $offset_y = 0,
        string $position = 'top-left'
    ): ImageInterface {
        return $this->modify(new CropModifier($width, $height, $offset_x, $offset_y, $position));
    }

    public function place(
        mixed $element,
        string $position = 'top-left',
        int $offset_x = 0,
        int $offset_y = 0
    ): ImageInterface {
        return $this->modify(new PlaceModifier($element, $position, $offset_x, $offset_y));
    }

    public function toJpg(int $quality = 75): EncodedImageInterface
    {
        return $this->toJpeg($quality);
    }

    public function toPng(int $color_limit = 0): EncodedImageInterface
    {
        return $this->encode(new PngEncoder($color_limit));
    }

    public function toGif(int $color_limit = 0): EncodedImageInterface
    {
        return $this->encode(new GifEncoder($color_limit));
    }

    public function toWebp(int $quality = 75): EncodedImageInterface
    {
        return $this->encode(new WebpEncoder($quality));
    }

    public function toBitmap(int $color_limit = 0): EncodedImageInterface
    {
        return $this->encode(new BmpEncoder($color_limit));
    }

    public function toBmp(int $color_limit = 0): EncodedImageInterface
    {
        return $this->toBitmap($color_limit);
    }

    public function toAvif(int $quality = 75): EncodedImageInterface
    {
        return $this->encode(new AvifEncoder($quality));
    }
}
