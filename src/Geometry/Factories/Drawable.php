<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry\Factories;

class Drawable
{
    /**
     * Creeate BezierFactory statically
     */
    public static function bezier(): BezierFactory
    {
        return new BezierFactory();
    }

    /**
     * Creeate CircleFactory statically
     */
    public static function circle(): CircleFactory
    {
        return new CircleFactory();
    }

    /**
     * Create EllipseFactory statically
     */
    public static function ellipse(): EllipseFactory
    {
        return new EllipseFactory();
    }

    /**
     * Creeate LineFactory statically
     */
    public static function line(): LineFactory
    {
        return new LineFactory();
    }

    /**
     * Creeate PolygonFactory statically
     */
    public static function polygon(): PolygonFactory
    {
        return new PolygonFactory();
    }

    /**
     * Creeate RectangleFactory statically
     */
    public static function rectangle(): RectangleFactory
    {
        return new RectangleFactory();
    }
}
