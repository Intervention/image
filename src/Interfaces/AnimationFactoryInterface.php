<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Fraction;
use Intervention\Image\Geometry\Bezier;
use Intervention\Image\Geometry\Circle;
use Intervention\Image\Geometry\Ellipse;
use Intervention\Image\Geometry\Line;
use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Geometry\Rectangle;

/**
 * @method AnimationFactoryInterface analyze(AnalyzerInterface $analyzer)
 * @method AnimationFactoryInterface blur(int $amount = 5)
 * @method AnimationFactoryInterface brightness(int $level)
 * @method AnimationFactoryInterface colorAt(int $x, int $y, int $frame = 0)
 * @method AnimationFactoryInterface colorize(int $red = 0, int $green = 0, int $blue = 0)
 * @method AnimationFactoryInterface colorsAt(int $x, int $y)
 * @method AnimationFactoryInterface contain(int|Fraction $width, int|Fraction $height, null|string|ColorInterface $background = null, string|Alignment $alignment = Alignment)
 * @method AnimationFactoryInterface contrast(int $level)
 * @method AnimationFactoryInterface cover(int|Fraction $width, int|Fraction $height, string|Alignment $alignment = Alignment)
 * @method AnimationFactoryInterface coverDown(int|Fraction $width, int|Fraction $height, string|Alignment $alignment = Alignment)
 * @method AnimationFactoryInterface crop(int|Fraction $width, int|Fraction $height, int $x = 0, int $y = 0, null|string|ColorInterface $background = null, string|Alignment $alignment = Alignment)
 * @method AnimationFactoryInterface draw(DrawableInterface $drawable, ?callable $adjustments = null)
 * @method AnimationFactoryInterface draw(DrawableInterface $drawable, ?callable $adjustments = null)
 * @method AnimationFactoryInterface drawBezier(callable|Bezier $bezier, ?callable $adjustments = null)
 * @method AnimationFactoryInterface drawCircle(callable|Circle $circle, ?callable $adjustments = null)
 * @method AnimationFactoryInterface drawEllipse(callable|Ellipse $ellipse, ?callable $adjustments = null)
 * @method AnimationFactoryInterface drawLine(callable|Line $line, ?callable $adjustments = null)
 * @method AnimationFactoryInterface drawPixel(int $x, int $y, string|ColorInterface $color)
 * @method AnimationFactoryInterface drawPolygon(callable|Polygon $polygon, ?callable $adjustments = null)
 * @method AnimationFactoryInterface drawRectangle(callable|Rectangle $rectangle, ?callable $adjustments = null)
 * @method AnimationFactoryInterface fill(string|ColorInterface $color, ?int $x = null, ?int $y = null)
 * @method AnimationFactoryInterface fillTransparentAreas(null|string|ColorInterface $color = null)
 * @method AnimationFactoryInterface flip(Direction $direction = Direction
 * @method AnimationFactoryInterface gamma(float $gamma)
 * @method AnimationFactoryInterface grayscale()
 * @method AnimationFactoryInterface insert(mixed $image, int $x = 0, int $y = 0, string|Alignment $alignment = Alignment)
 * @method AnimationFactoryInterface invert()
 * @method AnimationFactoryInterface modify(ModifierInterface $modifier)
 * @method AnimationFactoryInterface orient()
 * @method AnimationFactoryInterface pad(int|Fraction $width, int|Fraction $height, null|string|ColorInterface $background = null, string|Alignment $alignment = Alignment)
 * @method AnimationFactoryInterface pixelate(int $size)
 * @method AnimationFactoryInterface reduceColors(int $limit, string|ColorInterface $background = 'transparent')
 * @method AnimationFactoryInterface removeAnimation(int|string $position = 0)
 * @method AnimationFactoryInterface resize(null|int|Fraction $width = null, null|int|Fraction $height = null)
 * @method AnimationFactoryInterface resize(null|int|Fraction $width = null, null|int|Fraction $height = null)
 * @method AnimationFactoryInterface resizeCanvas(null|int|Fraction $width = null, null|int|Fraction $height = null, null|string|ColorInterface $background = null, string|Alignment $alignment = Alignment)
 * @method AnimationFactoryInterface resizeCanvasRelative(null|int|Fraction $width = null, null|int|Fraction $height = null, null|string|ColorInterface $background = null, string|Alignment $alignment = Alignment)
 * @method AnimationFactoryInterface resizeDown(null|int|Fraction $width = null, null|int|Fraction $height = null)
 * @method AnimationFactoryInterface resizeDown(null|int|Fraction $width = null, null|int|Fraction $height = null)
 * @method AnimationFactoryInterface rotate(float $angle, null|string|ColorInterface $background = null)
 * @method AnimationFactoryInterface scale(null|int|Fraction $width = null, null|int|Fraction $height = null)
 * @method AnimationFactoryInterface scaleDown(null|int|Fraction $width = null, null|int|Fraction $height = null)
 * @method AnimationFactoryInterface sharpen(int $amount = 10)
 * @method AnimationFactoryInterface sliceAnimation(int $offset = 0, ?int $length = null)
 * @method AnimationFactoryInterface text(string $text, int $x, int $y, callable|FontInterface $font)
 * @method AnimationFactoryInterface trim(int $tolerance = 0)
 */
interface AnimationFactoryInterface
{
    /**
     * Create the animation of the factory statically by calling given callable.
     */
    public static function build(int $width, int $height, callable $animation, DriverInterface $driver): ImageInterface;

    /**
     * Resolve image or color from given source and add it as new animation frame with specific delay in seconds.
     */
    public function add(mixed $source, float $delay = 1): self;

    /**
     * Build ready-made animation as end product.
     */
    public function image(DriverInterface $driver): ImageInterface;
}
