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
use Intervention\Image\Geometry\Factories\CircleFactory;
use Intervention\Image\Geometry\Factories\EllipseFactory;
use Intervention\Image\Geometry\Factories\LineFactory;
use Intervention\Image\Geometry\Factories\PolygonFactory;
use Intervention\Image\Geometry\Factories\RectangleFactory;
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
use Intervention\Image\Modifiers\ContainModifier;
use Intervention\Image\Modifiers\ContrastModifier;
use Intervention\Image\Modifiers\CropModifier;
use Intervention\Image\Modifiers\DrawEllipseModifier;
use Intervention\Image\Modifiers\DrawLineModifier;
use Intervention\Image\Modifiers\DrawPixelModifier;
use Intervention\Image\Modifiers\DrawPolygonModifier;
use Intervention\Image\Modifiers\DrawRectangleModifier;
use Intervention\Image\Modifiers\FillModifier;
use Intervention\Image\Modifiers\CoverDownModifier;
use Intervention\Image\Modifiers\CoverModifier;
use Intervention\Image\Modifiers\FlipModifier;
use Intervention\Image\Modifiers\FlopModifier;
use Intervention\Image\Modifiers\GammaModifier;
use Intervention\Image\Modifiers\GreyscaleModifier;
use Intervention\Image\Modifiers\InvertModifier;
use Intervention\Image\Modifiers\PadModifier;
use Intervention\Image\Modifiers\PixelateModifier;
use Intervention\Image\Modifiers\PlaceModifier;
use Intervention\Image\Modifiers\ProfileModifier;
use Intervention\Image\Modifiers\ProfileRemovalModifier;
use Intervention\Image\Modifiers\RemoveAnimationModifier;
use Intervention\Image\Modifiers\ResizeCanvasModifier;
use Intervention\Image\Modifiers\ResizeCanvasRelativeModifier;
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

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::driver()
     */
    public function driver(): DriverInterface
    {
        return $this->driver;
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::core()
     */
    public function core(): CoreInterface
    {
        return $this->core;
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::count()
     */
    public function count(): int
    {
        return $this->core->count();
    }

    /**
     * Implementation of IteratorAggregate
     *
     * @return Traversable
     */
    public function getIterator(): Traversable
    {
        return $this->core;
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::isAnimated()
     */
    public function isAnimated(): bool
    {
        return $this->count() > 1;
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::removeAnimation(
     */
    public function removeAnimation(int|string $position = 0): ImageInterface
    {
        return $this->modify(new RemoveAnimationModifier($position));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::loops()
     */
    public function loops(): int
    {
        return $this->core->loops();
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::setLoops()
     */
    public function setLoops(int $loops): ImageInterface
    {
        $this->core->setLoops($loops);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::exif()
     */
    public function exif(?string $query = null): mixed
    {
        return is_null($query) ? $this->exif : $this->exif->get($query);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::modify()
     */
    public function modify(ModifierInterface $modifier): ImageInterface
    {
        return $this->driver->resolve($modifier)->apply($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::analyze()
     */
    public function analyze(AnalyzerInterface $analyzer): mixed
    {
        return $this->driver->resolve($analyzer)->analyze($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::encode()
     */
    public function encode(EncoderInterface $encoder): EncodedImage
    {
        return $this->driver->resolve($encoder)->encode($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::width()
     */
    public function width(): int
    {
        return $this->analyze(new WidthAnalyzer());
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::height()
     */
    public function height(): int
    {
        return $this->analyze(new HeightAnalyzer());
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::size()
     */
    public function size(): SizeInterface
    {
        return new Rectangle($this->width(), $this->height());
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::colorspace()
     */
    public function colorspace(): ColorspaceInterface
    {
        return $this->analyze(new ColorspaceAnalyzer());
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
     * @see ImageInterface::resolution()
     */
    public function resolution(): ResolutionInterface
    {
        return $this->analyze(new ResolutionAnalyzer());
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::setResolution()
     */
    public function setResolution(float $x, float $y): ImageInterface
    {
        return $this->modify(new ResolutionModifier($x, $y));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::pickColor()
     */
    public function pickColor(int $x, int $y, int $frame_key = 0): ColorInterface
    {
        return $this->analyze(new PixelColorAnalyzer($x, $y, $frame_key));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::pickColors()
     */
    public function pickColors(int $x, int $y): CollectionInterface
    {
        return $this->analyze(new PixelColorsAnalyzer($x, $y));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::profile()
     */
    public function profile(): ProfileInterface
    {
        return $this->analyze(new ProfileAnalyzer());
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::setProfile()
     */
    public function setProfile(ProfileInterface $profile): ImageInterface
    {
        return $this->modify(new ProfileModifier($profile));
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

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::sharpen()
     */
    public function sharpen(int $amount = 10): ImageInterface
    {
        return $this->modify(new SharpenModifier($amount));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::invert()
     */
    public function invert(): ImageInterface
    {
        return $this->modify(new InvertModifier());
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::pixelate()
     */
    public function pixelate(int $size): ImageInterface
    {
        return $this->modify(new PixelateModifier($size));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::greyscale()
     */
    public function greyscale(): ImageInterface
    {
        return $this->modify(new GreyscaleModifier());
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::brightness()
     */
    public function brightness(int $level): ImageInterface
    {
        return $this->modify(new BrightnessModifier($level));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::contrast()
     */
    public function contrast(int $level): ImageInterface
    {
        return $this->modify(new ContrastModifier($level));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::gamma()
     */
    public function gamma(float $gamma): ImageInterface
    {
        return $this->modify(new GammaModifier($gamma));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::colorize()
     */
    public function colorize(int $red = 0, int $green = 0, int $blue = 0): ImageInterface
    {
        return $this->modify(new ColorizeModifier($red, $green, $blue));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::flip()
     */
    public function flip(): ImageInterface
    {
        return $this->modify(new FlipModifier());
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::flop()
     */
    public function flop(): ImageInterface
    {
        return $this->modify(new FlopModifier());
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::blur()
     */
    public function blur(int $amount = 5): ImageInterface
    {
        return $this->modify(new BlurModifier($amount));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::rotate()
     */
    public function rotate(float $angle, mixed $background = 'ffffff'): ImageInterface
    {
        return $this->modify(new RotateModifier($angle, $background));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::text()
     */
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

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::resize()
     */
    public function resize(?int $width = null, ?int $height = null): ImageInterface
    {
        return $this->modify(new ResizeModifier($width, $height));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::resizeDown()
     */
    public function resizeDown(?int $width = null, ?int $height = null): ImageInterface
    {
        return $this->modify(new ResizeDownModifier($width, $height));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::scale()
     */
    public function scale(?int $width = null, ?int $height = null): ImageInterface
    {
        return $this->modify(new ScaleModifier($width, $height));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::scaleDown()
     */
    public function scaleDown(?int $width = null, ?int $height = null): ImageInterface
    {
        return $this->modify(new ScaleDownModifier($width, $height));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::cover()
     */
    public function cover(int $width, int $height, string $position = 'center'): ImageInterface
    {
        return $this->modify(new CoverModifier($width, $height, $position));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::coverDown()
     */
    public function coverDown(int $width, int $height, string $position = 'center'): ImageInterface
    {
        return $this->modify(new CoverDownModifier($width, $height, $position));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::resizeCanvas()
     */
    public function resizeCanvas(
        ?int $width = null,
        ?int $height = null,
        mixed $background = 'ffffff',
        string $position = 'center'
    ): ImageInterface {
        return $this->modify(new ResizeCanvasModifier($width, $height, $background, $position));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::resizeCanvasRelative()
     */
    public function resizeCanvasRelative(
        ?int $width = null,
        ?int $height = null,
        mixed $background = 'ffffff',
        string $position = 'center'
    ): ImageInterface {
        return $this->modify(new ResizeCanvasRelativeModifier($width, $height, $background, $position));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::padDown()
     */
    public function pad(
        int $width,
        int $height,
        mixed $background = 'ffffff',
        string $position = 'center'
    ): ImageInterface {
        return $this->modify(new PadModifier($width, $height, $background, $position));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::pad()
     */
    public function contain(
        int $width,
        int $height,
        mixed $background = 'ffffff',
        string $position = 'center'
    ): ImageInterface {
        return $this->modify(new ContainModifier($width, $height, $background, $position));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::crop()
     */
    public function crop(
        int $width,
        int $height,
        int $offset_x = 0,
        int $offset_y = 0,
        string $position = 'top-left'
    ): ImageInterface {
        return $this->modify(new CropModifier($width, $height, $offset_x, $offset_y, $position));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::place()
     */
    public function place(
        mixed $element,
        string $position = 'top-left',
        int $offset_x = 0,
        int $offset_y = 0
    ): ImageInterface {
        return $this->modify(new PlaceModifier($element, $position, $offset_x, $offset_y));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::fill()
     */
    public function fill(mixed $color, ?int $x = null, ?int $y = null): ImageInterface
    {
        return $this->modify(
            new FillModifier(
                $color,
                (is_null($x) || is_null($y)) ? null : new Point($x, $y),
            ),
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::drawPixel()
     */
    public function drawPixel(int $x, int $y, mixed $color): ImageInterface
    {
        return $this->modify(new DrawPixelModifier(new Point($x, $y), $color));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::drawRectangle()
     */
    public function drawRectangle(int $x, int $y, callable|Rectangle $init): ImageInterface
    {
        return $this->modify(
            new DrawRectangleModifier(
                call_user_func(new RectangleFactory(new Point($x, $y), $init)),
            ),
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::drawEllipse()
     */
    public function drawEllipse(int $x, int $y, callable $init): ImageInterface
    {
        return $this->modify(
            new DrawEllipseModifier(
                call_user_func(new EllipseFactory(new Point($x, $y), $init)),
            ),
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::drawCircle()
     */
    public function drawCircle(int $x, int $y, callable $init): ImageInterface
    {
        return $this->modify(
            new DrawEllipseModifier(
                call_user_func(new CircleFactory(new Point($x, $y), $init)),
            ),
        );
    }

    public function drawPolygon(callable $init): ImageInterface
    {
        return $this->modify(
            new DrawPolygonModifier(
                call_user_func(new PolygonFactory($init)),
            ),
        );
    }

    public function drawLine(callable $init): ImageInterface
    {
        return $this->modify(
            new DrawLineModifier(
                call_user_func(new LineFactory($init)),
            ),
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::toJpeg()
     */
    public function toJpeg(int $quality = 75): EncodedImageInterface
    {
        return $this->encode(new JpegEncoder($quality));
    }

    /**
     * Alias of self::toJpeg()
     *
     * @param int $quality
     * @return EncodedImageInterface
     */
    public function toJpg(int $quality = 75): EncodedImageInterface
    {
        return $this->toJpeg($quality);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::toPng()
     */
    public function toPng(int $color_limit = 0): EncodedImageInterface
    {
        return $this->encode(new PngEncoder($color_limit));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::toGif()
     */
    public function toGif(int $color_limit = 0): EncodedImageInterface
    {
        return $this->encode(new GifEncoder($color_limit));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::toWebp()
     */
    public function toWebp(int $quality = 75): EncodedImageInterface
    {
        return $this->encode(new WebpEncoder($quality));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::toBitmap()
     */
    public function toBitmap(int $color_limit = 0): EncodedImageInterface
    {
        return $this->encode(new BmpEncoder($color_limit));
    }

    /**
     * Alias if self::toBitmap()
     *
     * @param int $color_limit
     * @return EncodedImageInterface
     */
    public function toBmp(int $color_limit = 0): EncodedImageInterface
    {
        return $this->toBitmap($color_limit);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::toAvif()
     */
    public function toAvif(int $quality = 75): EncodedImageInterface
    {
        return $this->encode(new AvifEncoder($quality));
    }
}
