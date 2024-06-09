<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry\Factories;

class Drawable
{
    /**
     * Creeate BezierFactory statically
     *
     * @return BezierFactory
     */
    public static function bezier(): BezierFactory
    {
        return new BezierFactory();
    }

    /**
     * Creeate CircleFactory statically
     *
     * @return CircleFactory
     */
    public static function circle(): CircleFactory
    {
        return new CircleFactory();
    }

    /**
     *
     * Create EllipseFactory statically
     *
     * @return EllipseFactory
     */
    public static function ellipse(): EllipseFactory
    {
        return new EllipseFactory();
    }

    /**
     * Creeate LineFactory statically
     *
     * @return LineFactory
     */
    public static function line(): LineFactory
    {
        return new LineFactory();
    }

    /**
     * Creeate PolygonFactory statically
     *
     * @return PolygonFactory
     */
    public static function polygon(): PolygonFactory
    {
        return new PolygonFactory();
    }

    /**
     * Creeate RectangleFactory statically
     *
     * @return RectangleFactory
     */
    public static function rectangle(): RectangleFactory
    {
        return new RectangleFactory();
    }
}
