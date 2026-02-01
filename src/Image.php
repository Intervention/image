<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Analyzers\ColorspaceAnalyzer;
use Intervention\Image\Analyzers\HeightAnalyzer;
use Intervention\Image\Analyzers\PixelColorAnalyzer;
use Intervention\Image\Analyzers\PixelColorsAnalyzer;
use Intervention\Image\Analyzers\ProfileAnalyzer;
use Intervention\Image\Analyzers\ResolutionAnalyzer;
use Intervention\Image\Analyzers\WidthAnalyzer;
use Intervention\Image\Encoders\AutoEncoder;
use Intervention\Image\Encoders\FileExtensionEncoder;
use Intervention\Image\Encoders\FilePathEncoder;
use Intervention\Image\Encoders\FormatEncoder;
use Intervention\Image\Encoders\MediaTypeEncoder;
use Intervention\Image\Exceptions\EncoderException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Geometry\Bezier;
use Intervention\Image\Geometry\Circle;
use Intervention\Image\Geometry\Ellipse;
use Intervention\Image\Geometry\Factories\BezierFactory;
use Intervention\Image\Geometry\Factories\CircleFactory;
use Intervention\Image\Geometry\Factories\EllipseFactory;
use Intervention\Image\Geometry\Factories\LineFactory;
use Intervention\Image\Geometry\Factories\PolygonFactory;
use Intervention\Image\Geometry\Factories\RectangleFactory;
use Intervention\Image\Geometry\Line;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Polygon;
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
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\ProfileInterface;
use Intervention\Image\Interfaces\ResolutionInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Modifiers\AlignRotationModifier;
use Intervention\Image\Modifiers\FillTransparentAreasModifier;
use Intervention\Image\Modifiers\BlurModifier;
use Intervention\Image\Modifiers\BrightnessModifier;
use Intervention\Image\Modifiers\ColorizeModifier;
use Intervention\Image\Modifiers\ColorspaceModifier;
use Intervention\Image\Modifiers\ContainModifier;
use Intervention\Image\Modifiers\ContrastModifier;
use Intervention\Image\Modifiers\CoverDownModifier;
use Intervention\Image\Modifiers\CoverModifier;
use Intervention\Image\Modifiers\CropModifier;
use Intervention\Image\Modifiers\DrawBezierModifier;
use Intervention\Image\Modifiers\DrawEllipseModifier;
use Intervention\Image\Modifiers\DrawLineModifier;
use Intervention\Image\Modifiers\DrawPixelModifier;
use Intervention\Image\Modifiers\DrawPolygonModifier;
use Intervention\Image\Modifiers\DrawRectangleModifier;
use Intervention\Image\Modifiers\FillModifier;
use Intervention\Image\Modifiers\FlipModifier;
use Intervention\Image\Modifiers\FlopModifier;
use Intervention\Image\Modifiers\GammaModifier;
use Intervention\Image\Modifiers\GrayscaleModifier;
use Intervention\Image\Modifiers\InsertModifier;
use Intervention\Image\Modifiers\InvertModifier;
use Intervention\Image\Modifiers\PadModifier;
use Intervention\Image\Modifiers\PixelateModifier;
use Intervention\Image\Modifiers\ProfileModifier;
use Intervention\Image\Modifiers\ProfileRemovalModifier;
use Intervention\Image\Modifiers\QuantizeColorsModifier;
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
use Intervention\Image\Modifiers\SliceAnimationModifier;
use Intervention\Image\Modifiers\TextModifier;
use Intervention\Image\Modifiers\TrimModifier;
use Intervention\Image\Typography\FontFactory;
use Throwable;
use Traversable;

final class Image implements ImageInterface
{
    /**
     * Origin containing the source from which it was originally created.
     */
    private Origin $origin;

    /**
     * Exif data of the current image.
     */
    private CollectionInterface $exif;

    /**
     * Create new instance.
     */
    public function __construct(
        private DriverInterface $driver,
        private CoreInterface $core,
    ) {
        $this->origin = new Origin();
        $this->exif = new Collection();
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
     * @see ImageInterface::origin()
     */
    public function origin(): Origin
    {
        return $this->origin;
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::setOrigin()
     */
    public function setOrigin(Origin $origin): ImageInterface
    {
        $this->origin = $origin;

        return $this;
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
     * @return Traversable<FrameInterface>
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
     * @see ImageInterface::sliceAnimation()
     */
    public function sliceAnimation(int $offset = 0, ?int $length = null): ImageInterface
    {
        return $this->modify(new SliceAnimationModifier($offset, $length));
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
     * @see ImageInterface::setExif()
     */
    public function setExif(CollectionInterface $exif): ImageInterface
    {
        $this->exif = $exif;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::modify()
     */
    public function modify(ModifierInterface $modifier): ImageInterface
    {
        return $this->driver()->specializeModifier($modifier)->apply($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::analyze()
     */
    public function analyze(AnalyzerInterface $analyzer): mixed
    {
        return $this->driver()->specializeAnalyzer($analyzer)->analyze($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::save()
     *
     * @throws EncoderException
     */
    public function save(?string $path = null, mixed ...$options): ImageInterface
    {
        if (is_null($path) && is_null($this->origin()->filePath())) {
            throw new EncoderException('Unable to determine path for saving');
        }

        $path = is_null($path) ? $this->origin()->filePath() : $path;

        try {
            // try to determine encoding format by file extension of the path
            $encoded = $this->encode(new FilePathEncoder($path, ...$options));
        } catch (EncoderException) {
            // fallback to encoding format by media type
            $encoded = $this->encode(new MediaTypeEncoder(null, ...$options));
        }

        $encoded->save($path);

        return $this;
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
     *
     * @throws InvalidArgumentException
     */
    public function size(): SizeInterface
    {
        return new Size($this->width(), $this->height());
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
     * @see ImageInterface::colorAt()
     */
    public function colorAt(int $x, int $y, int $frame = 0): ColorInterface
    {
        return $this->analyze(new PixelColorAnalyzer($x, $y, $frame));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::colorsAt()
     */
    public function colorsAt(int $x, int $y): CollectionInterface
    {
        return $this->analyze(new PixelColorsAnalyzer($x, $y));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::backgroundColor()
     */
    public function backgroundColor(): ColorInterface
    {
        return $this->driver()->handleColorInput(
            $this->driver()->config()->backgroundColor
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::setBackgroundColor()
     *
     * @throws InvalidArgumentException
     */
    public function setBackgroundColor(string|ColorInterface $color): ImageInterface
    {
        $this->driver()->config()->setOptions(
            backgroundColor: $this->driver()->handleColorInput($color)
        );

        return $this;
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
     * @see ImageInterface::reduceColors()
     */
    public function reduceColors(int $limit, string|ColorInterface $background = 'transparent'): ImageInterface
    {
        return $this->modify(new QuantizeColorsModifier($limit, $background));
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
     * @see ImageInterface::grayscale()
     */
    public function grayscale(): ImageInterface
    {
        return $this->modify(new GrayscaleModifier());
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
    public function rotate(float $angle, null|string|ColorInterface $background = null): ImageInterface
    {
        return $this->modify(new RotateModifier($angle, $background));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::orient()
     */
    public function orient(): ImageInterface
    {
        return $this->modify(new AlignRotationModifier());
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
                FontFactory::build($font),
            ),
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::resize()
     *
     * @throws InvalidArgumentException
     */
    public function resize(null|int|Fraction $width = null, null|int|Fraction $height = null): ImageInterface
    {
        return $this->modify(new ResizeModifier(...$this->fractionize($width, $height)));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::resizeDown()
     *
     * @throws InvalidArgumentException
     */
    public function resizeDown(null|int|Fraction $width = null, null|int|Fraction $height = null): ImageInterface
    {
        return $this->modify(new ResizeDownModifier(...$this->fractionize($width, $height)));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::scale()
     *
     * @throws InvalidArgumentException
     */
    public function scale(null|int|Fraction $width = null, null|int|Fraction $height = null): ImageInterface
    {
        return $this->modify(new ScaleModifier(...$this->fractionize($width, $height)));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::scaleDown()
     *
     * @throws InvalidArgumentException
     */
    public function scaleDown(null|int|Fraction $width = null, null|int|Fraction $height = null): ImageInterface
    {
        return $this->modify(new ScaleDownModifier(...$this->fractionize($width, $height)));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::cover()
     *
     * @throws InvalidArgumentException
     */
    public function cover(
        int|Fraction $width,
        int|Fraction $height,
        string|Alignment $alignment = Alignment::CENTER,
    ): ImageInterface {
        return $this->modify(new CoverModifier(...[
            ...$this->fractionize($width, $height),
            ...['alignment' => $alignment]
        ]));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::coverDown()
     *
     * @throws InvalidArgumentException
     */
    public function coverDown(
        int|Fraction $width,
        int|Fraction $height,
        string|Alignment $alignment = Alignment::CENTER,
    ): ImageInterface {
        return $this->modify(new CoverDownModifier(...[
            ...$this->fractionize($width, $height),
            ...['alignment' => $alignment]
        ]));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::resizeCanvas()
     *
     * @throws InvalidArgumentException
     */
    public function resizeCanvas(
        null|int|Fraction $width = null,
        null|int|Fraction $height = null,
        null|string|ColorInterface $background = null,
        string|Alignment $alignment = Alignment::CENTER
    ): ImageInterface {
        return $this->modify(new ResizeCanvasModifier(...[
            ...$this->fractionize($width, $height),
            ...[
                'background' => $background,
                'alignment' => $alignment,
            ]
        ]));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::resizeCanvasRelative()
     *
     * @throws InvalidArgumentException
     */
    public function resizeCanvasRelative(
        null|int|Fraction $width = null,
        null|int|Fraction $height = null,
        null|string|ColorInterface $background = null,
        string|Alignment $alignment = Alignment::CENTER
    ): ImageInterface {
        return $this->modify(new ResizeCanvasRelativeModifier(...[
            ...$this->fractionize($width, $height),
            ...[
                'background' => $background,
                'alignment' => $alignment,
            ]
        ]));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::padDown()
     *
     * @throws InvalidArgumentException
     */
    public function pad(
        int|Fraction $width,
        int|Fraction $height,
        null|string|ColorInterface $background = null,
        string|Alignment $alignment = Alignment::CENTER
    ): ImageInterface {
        return $this->modify(new PadModifier(...[
            ...$this->fractionize($width, $height),
            ...[
                'background' => $background,
                'alignment' => $alignment,
            ]
        ]));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::pad()
     *
     * @throws InvalidArgumentException
     */
    public function contain(
        int|Fraction $width,
        int|Fraction $height,
        null|string|ColorInterface $background = null,
        string|Alignment $alignment = Alignment::CENTER
    ): ImageInterface {
        return $this->modify(new ContainModifier(...[
            ...$this->fractionize($width, $height),
            ...[
                'background' => $background,
                'alignment' => $alignment,
            ]
        ]));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::crop()
     *
     * @throws InvalidArgumentException
     */
    public function crop(
        int|Fraction $width,
        int|Fraction $height,
        int $x = 0,
        int $y = 0,
        null|string|ColorInterface $background = null,
        string|Alignment $alignment = Alignment::TOP_LEFT
    ): ImageInterface {
        return $this->modify(new CropModifier(...[
            ...$this->fractionize($width, $height),
            ...[
                'x' => $x,
                'y' => $y,
                'background' => $background,
                'alignment' => $alignment,
            ]
        ]));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::trim()
     */
    public function trim(int $tolerance = 0): ImageInterface
    {
        return $this->modify(new TrimModifier($tolerance));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::insert()
     */
    public function insert(
        mixed $image,
        int $x = 0,
        int $y = 0,
        string|Alignment $alignment = Alignment::TOP_LEFT,
        int $opacity = 100
    ): ImageInterface {
        return $this->modify(new InsertModifier($image, $x, $y, $alignment, $opacity));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::fill()
     */
    public function fill(string|ColorInterface $color, ?int $x = null, ?int $y = null): ImageInterface
    {
        return $this->modify(
            new FillModifier(
                $color,
                is_null($x) || is_null($y) ? null : new Point($x, $y),
            ),
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::fillTransparentAreas()
     */
    public function fillTransparentAreas(null|string|ColorInterface $color = null): ImageInterface
    {
        return $this->modify(new FillTransparentAreasModifier($color));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::drawPixel()
     */
    public function drawPixel(int $x, int $y, string|ColorInterface $color): ImageInterface
    {
        return $this->modify(new DrawPixelModifier(new Point($x, $y), $color));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::drawRectangle()
     *
     * @throws InvalidArgumentException
     */
    public function drawRectangle(int $x, int $y, callable|Rectangle $rectangle): ImageInterface
    {
        return $this->modify(
            new DrawRectangleModifier(
                RectangleFactory::build($rectangle)->setPosition(new Point($x, $y)),
            ),
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::drawEllipse()
     */
    public function drawEllipse(int $x, int $y, callable|Ellipse $ellipse): ImageInterface
    {
        return $this->modify(
            new DrawEllipseModifier(
                EllipseFactory::build($ellipse)->setPosition(new Point($x, $y)),
            ),
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::drawCircle()
     */
    public function drawCircle(int $x, int $y, callable|Circle $circle): ImageInterface
    {
        return $this->modify(
            new DrawEllipseModifier(
                CircleFactory::build($circle)->setPosition(new Point($x, $y))
            ),
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::drawPolygon()
     */
    public function drawPolygon(callable|Polygon $polygon): ImageInterface
    {
        return $this->modify(
            new DrawPolygonModifier(
                PolygonFactory::build($polygon)
            ),
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::drawLine()
     */
    public function drawLine(callable|Line $line): ImageInterface
    {
        return $this->modify(
            new DrawLineModifier(
                LineFactory::build($line)
            ),
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::drawBezier()
     */
    public function drawBezier(callable|Bezier $bezier): ImageInterface
    {
        return $this->modify(
            new DrawBezierModifier(
                BezierFactory::build($bezier)
            ),
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::encode()
     */
    public function encode(null|string|EncoderInterface $encoder = new AutoEncoder()): EncodedImageInterface
    {
        return $this->driver()->specializeEncoder(
            is_string($encoder) ? new $encoder() : $encoder
        )->encode($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::encodeUsingFormat()
     */
    public function encodeUsingFormat(Format $format, mixed ...$options): EncodedImageInterface
    {
        return $this->encode(new FormatEncoder($format, ...$options));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::encodeUsingMediaType()
     */
    public function encodeUsingMediaType(string|MediaType $mediaType, mixed ...$options): EncodedImageInterface
    {
        return $this->encode(new MediaTypeEncoder($mediaType, ...$options));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::encodeUsingFileExtension()
     */
    public function encodeUsingFileExtension(
        string|FileExtension $fileExtension,
        mixed ...$options,
    ): EncodedImageInterface {
        return $this->encode(new FileExtensionEncoder($fileExtension, ...$options));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::encodeUsingPath()
     */
    public function encodeUsingPath(string $path, mixed ...$options,): EncodedImageInterface
    {
        return $this->encode(new FilePathEncoder($path, ...$options));
    }

    /**
     * Build array of resize width and height from various inputs including
     * fractions based on the current image size.
     *
     * @throws InvalidArgumentException
     * @return array{'width': ?int, 'height': ?int}
     */
    private function fractionize(null|int|Fraction $width, null|int|Fraction $height): array
    {
        if ($width instanceof Fraction || $height instanceof Fraction) {
            $size = $this->size();
            $width = ($width instanceof Fraction) ? (int) round($width->of($size->width())) : $width;
            $height = ($height instanceof Fraction) ? (int) round($height->of($size->height())) : $height;
        }

        return [
            'width' => $width,
            'height' => $height,
        ];
    }

    /**
     * Show debug info for the current image.
     *
     * @return array<string, ?int>
     */
    public function __debugInfo(): array
    {
        try {
            return [
                'width' => $this->width(),
                'height' => $this->height(),
            ];
        } catch (Throwable) {
            return [
                'width' => null,
                'height' => null,
            ];
        }
    }

    /**
     * Clone image.
     */
    public function __clone(): void
    {
        $this->driver = clone $this->driver;
        $this->core = clone $this->core;
        $this->exif = clone $this->exif;
    }
}
