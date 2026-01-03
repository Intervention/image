<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry\Factories;

use Intervention\Image\Geometry\Bezier;
use Intervention\Image\Geometry\Circle;
use Intervention\Image\Geometry\Ellipse;
use Intervention\Image\Geometry\Line;
use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Geometry\Rectangle;

class Drawable
{
    /**
     * Create Bezier statically.
     */
    public static function bezier(null|callable|Bezier $bezier = null): Bezier
    {
        return BezierFactory::build($bezier);
    }

    /**
     * Create Circle statically.
     */
    public static function circle(null|callable|Circle $circle = null): Circle
    {
        return CircleFactory::build($circle);
    }

    /**
     * Create Ellipse statically.
     */
    public static function ellipse(null|callable|Ellipse $ellipse = null): Ellipse
    {
        return EllipseFactory::build($ellipse);
    }

    /**
     * Create Line statically.
     */
    public static function line(null|callable|Line $line = null): Line
    {
        return LineFactory::build($line);
    }

    /**
     * Create Polygon statically.
     */
    public static function polygon(null|callable|Polygon $polygon = null): Polygon
    {
        return PolygonFactory::build($polygon);
    }

    /**
     * Create Rectangle statically.
     */
    public static function rectangle(null|callable|Rectangle $rectangle = null): Rectangle
    {
        return RectangleFactory::build($rectangle);
    }
}
