<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Alignment;
use Intervention\Image\Direction;
use Intervention\Image\Fraction;

// phpcs:disable Generic.Files.LineLength
/**
 * @method ImageInterface width()
 * @method ImageInterface height()
 * @method ImageInterface size()
 * @method ImageInterface modify(ModifierInterface $modifier)
 * @method ImageInterface analyze(AnalyzerInterface $analyzer)
 * @method ImageInterface isAnimated()
 * @method ImageInterface removeAnimation(int|string $position = 0)
 * @method ImageInterface sliceAnimation(int $offset = 0, ?int $length = null)
 * @method ImageInterface colorAt(int $x, int $y, int $frame = 0)
 * @method ImageInterface colorsAt(int $x, int $y)
 * @method ImageInterface fillTransparentAreas(null|string|ColorInterface $color = null)
 * @method ImageInterface reduceColors(int $limit, null|string|ColorInterface $background = null)
 * @method ImageInterface sharpen(int $level = 10)
 * @method ImageInterface grayscale()
 * @method ImageInterface brightness(int $level)
 * @method ImageInterface contrast(int $level)
 * @method ImageInterface gamma(float $gamma)
 * @method ImageInterface colorize(int $red = 0, int $green = 0, int $blue = 0)
 * @method ImageInterface flip(Direction $direction = Direction::HORIZONTAL)
 * @method ImageInterface blur(int $level = 5)
 * @method ImageInterface invert()
 * @method ImageInterface pixelate(int $size)
 * @method ImageInterface rotate(float $angle, null|string|ColorInterface $background = null)
 * @method ImageInterface orient()
 * @method ImageInterface text(string $text, int $x, int $y, callable|FontInterface $font)
 * @method ImageInterface resize(null|int|Fraction $width = null, null|int|Fraction $height = null)
 * @method ImageInterface resizeDown(null|int|Fraction $width = null, null|int|Fraction $height = null)
 * @method ImageInterface scale(null|int|Fraction $width = null, null|int|Fraction $height = null)
 * @method ImageInterface scaleDown(null|int|Fraction $width = null, null|int|Fraction $height = null)
 * @method ImageInterface cover(int|Fraction $width, int|Fraction $height, string|Alignment $alignment = Alignment::CENTER)
 * @method ImageInterface coverDown(int|Fraction $width, int|Fraction $height, string|Alignment $alignment = Alignment::CENTER)
 * @method ImageInterface resizeCanvas(null|int|Fraction $width = null, null|int|Fraction $height = null, null|string|ColorInterface $background = null, string|Alignment $alignment = Alignment::CENTER)
 */
// phpcs:enable Generic.Files.LineLength
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
