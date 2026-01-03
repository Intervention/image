<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry\Factories;

use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\DrawableInterface;

class Drawable
{
    /**
     * Creeate BezierFactory statically.
     */
    public static function bezier(null|callable|DrawableInterface $init = null): BezierFactory
    {
        return BezierFactory::create($init);
    }

    /**
     * Creeate CircleFactory statically.
     */
    public static function circle(null|callable|DrawableInterface $init = null): CircleFactory
    {
        return CircleFactory::create($init);
    }

    /**
     * Create EllipseFactory statically.
     */
    public static function ellipse(null|callable|DrawableInterface $init = null): EllipseFactory
    {
        return EllipseFactory::create($init);
    }

    /**
     * Creeate LineFactory statically.
     */
    public static function line(null|callable|DrawableInterface $init = null): LineFactory
    {
        return LineFactory::create($init);
    }

    /**
     * Creeate PolygonFactory statically.
     */
    public static function polygon(null|callable|DrawableInterface $init = null): PolygonFactory
    {
        return PolygonFactory::create($init);
    }

    /**
     * Creeate RectangleFactory statically.
     *
     * @throws InvalidArgumentException
     */
    public static function rectangle(null|callable|DrawableInterface $init = null): RectangleFactory
    {
        return RectangleFactory::create($init);
    }
}
