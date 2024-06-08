<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry\Factories;

class Drawable
{
    /**
     * Create circle factory statically
     *
     * @return CircleFactory
     */
    public static function circle(): CircleFactory
    {
        return new CircleFactory();
    }

    /**
     * Create ellipse factory statically
     *
     * @return EllipseFactory
     */
    public static function ellipse(): EllipseFactory
    {
        return new EllipseFactory();
    }

    /**
     * Create line factory statically
     *
     * @return LineFactory
     */
    public static function line(): LineFactory
    {
        return new LineFactory();
    }

    /**
     * Create polygon factory statically
     *
     * @return PolygonFactory
     */
    public static function polygon(): PolygonFactory
    {
        return new PolygonFactory();
    }

    /**
     * Create rectangle factory statically
     *
     * @return RectangleFactory
     */
    public static function rectangle(): RectangleFactory
    {
        return new RectangleFactory();
    }
}
