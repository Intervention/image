<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Fraction;

/**
 * @method AnimationFactoryInterface draw(DrawableInterface $drawable, ?callable $adjustments = null)
 * @method AnimationFactoryInterface resize(null|int|Fraction $width = null, null|int|Fraction $height = null)
 * @method AnimationFactoryInterface resizeDown(null|int|Fraction $width = null, null|int|Fraction $height = null)
 *
 * TODO: add remaining ImageInterface methods
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
