<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use RuntimeException;
use Intervention\Image\Exceptions\GeometryException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\DrawBezierModifier as ModifiersDrawBezierModifier;

class DrawBezierModifier extends ModifiersDrawBezierModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     *
     * @throws RuntimeException
     * @throws GeometryException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            if ($this->drawable->count() !== 3 && $this->drawable->count() !== 4) {
                throw new GeometryException('You must specify either 3 or 4 points to create a bezier curve');
            }

            [$polygon, $polygon_border_segments] = $this->calculateBezierPoints();

            if ($this->drawable->hasBackgroundColor() || $this->drawable->hasBorder()) {
                imagealphablending($frame->native(), true);
                imageantialias($frame->native(), true);
            }

            if ($this->drawable->hasBackgroundColor()) {
                $background_color = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
                    $this->backgroundColor()
                );

                imagesetthickness($frame->native(), 0);
                imagefilledpolygon(
                    $frame->native(),
                    $polygon,
                    $background_color
                );
            }

            if ($this->drawable->hasBorder() && $this->drawable->borderSize() > 0) {
                $border_color = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
                    $this->borderColor()
                );

                if ($this->drawable->borderSize() === 1) {
                    imagesetthickness($frame->native(), $this->drawable->borderSize());

                    $count = count($polygon);
                    for ($i = 0; $i < $count; $i += 2) {
                        if (array_key_exists($i + 2, $polygon) && array_key_exists($i + 3, $polygon)) {
                            imageline(
                                $frame->native(),
                                $polygon[$i + 0],
                                $polygon[$i + 1],
                                $polygon[$i + 2],
                                $polygon[$i + 3],
                                $border_color
                            );
                        }
                    }
                } else {
                    $polygon_border_segments_total = count($polygon_border_segments);

                    for ($i = 0; $i < $polygon_border_segments_total; $i += 1) {
                        imagefilledpolygon(
                            $frame->native(),
                            $polygon_border_segments[$i],
                            $border_color
                        );
                    }
                }
            }
        }

        return $image;
    }

    /**
     * Calculate interpolation points for quadratic beziers using the Bernstein polynomial form
     *
     * @param float $t
     * @return array{'x': float, 'y': float}
     */
    private function calculateQuadraticBezierInterpolationPoint(float $t = 0.05): array
    {
        $remainder = 1 - $t;
        $control_point_1_multiplier = $remainder * $remainder;
        $control_point_2_multiplier = $remainder * $t * 2;
        $control_point_3_multiplier = $t * $t;

        $x = (
            $this->drawable->first()->x() * $control_point_1_multiplier +
            $this->drawable->second()->x() * $control_point_2_multiplier +
            $this->drawable->last()->x() * $control_point_3_multiplier
        );
        $y = (
            $this->drawable->first()->y() * $control_point_1_multiplier +
            $this->drawable->second()->y() * $control_point_2_multiplier +
            $this->drawable->last()->y() * $control_point_3_multiplier
        );

        return ['x' => $x, 'y' => $y];
    }

    /**
     * Calculate interpolation points for cubic beziers using the Bernstein polynomial form
     *
     * @param float $t
     * @return array{'x': float, 'y': float}
     */
    private function calculateCubicBezierInterpolationPoint(float $t = 0.05): array
    {
        $remainder = 1 - $t;
        $t_squared = $t * $t;
        $remainder_squared = $remainder * $remainder;
        $control_point_1_multiplier = $remainder_squared * $remainder;
        $control_point_2_multiplier = $remainder_squared * $t * 3;
        $control_point_3_multiplier = $t_squared * $remainder * 3;
        $control_point_4_multiplier = $t_squared * $t;

        $x = (
            $this->drawable->first()->x() * $control_point_1_multiplier +
            $this->drawable->second()->x() * $control_point_2_multiplier +
            $this->drawable->third()->x() * $control_point_3_multiplier +
            $this->drawable->last()->x() * $control_point_4_multiplier
        );
        $y = (
            $this->drawable->first()->y() * $control_point_1_multiplier +
            $this->drawable->second()->y() * $control_point_2_multiplier +
            $this->drawable->third()->y() * $control_point_3_multiplier +
            $this->drawable->last()->y() * $control_point_4_multiplier
        );

        return ['x' => $x, 'y' => $y];
    }

    /**
     * Calculate the points needed to draw a quadratic or cubic bezier with optional border/stroke
     *
     * @throws GeometryException
     * @return array{0: array<mixed>, 1: array<mixed>}
     */
    private function calculateBezierPoints(): array
    {
        if ($this->drawable->count() !== 3 && $this->drawable->count() !== 4) {
            throw new GeometryException('You must specify either 3 or 4 points to create a bezier curve');
        }

        $polygon = [];
        $inner_polygon = [];
        $outer_polygon = [];
        $polygon_border_segments = [];

        // define ratio t; equivalent to 5 percent distance along edge
        $t = 0.05;

        $polygon[] = $this->drawable->first()->x();
        $polygon[] = $this->drawable->first()->y();
        for ($i = $t; $i < 1; $i += $t) {
            if ($this->drawable->count() === 3) {
                $ip = $this->calculateQuadraticBezierInterpolationPoint($i);
            } elseif ($this->drawable->count() === 4) {
                $ip = $this->calculateCubicBezierInterpolationPoint($i);
            }
            $polygon[] = (int) $ip['x'];
            $polygon[] = (int) $ip['y'];
        }
        $polygon[] = $this->drawable->last()->x();
        $polygon[] = $this->drawable->last()->y();

        if ($this->drawable->hasBorder() && $this->drawable->borderSize() > 1) {
            // create the border/stroke effect by calculating two new curves with offset positions
            // from the main polygon and then connecting the inner/outer curves to create separate
            // 4-point polygon segments
            $polygon_total_points = count($polygon);
            $offset = ($this->drawable->borderSize() / 2);

            for ($i = 0; $i < $polygon_total_points; $i += 2) {
                if (array_key_exists($i + 2, $polygon) && array_key_exists($i + 3, $polygon)) {
                    $dx = $polygon[$i + 2] - $polygon[$i];
                    $dy = $polygon[$i + 3] - $polygon[$i + 1];
                    $dxy_sqrt = ($dx * $dx + $dy * $dy) ** 0.5;

                    // inner polygon
                    $scale = $offset / $dxy_sqrt;
                    $ox = -$dy * $scale;
                    $oy = $dx * $scale;

                    $inner_polygon[] = $ox + $polygon[$i + 0];
                    $inner_polygon[] = $oy + $polygon[$i + 1];
                    $inner_polygon[] = $ox + $polygon[$i + 2];
                    $inner_polygon[] = $oy + $polygon[$i + 3];

                    // outer polygon
                    $scale = -$offset / $dxy_sqrt;
                    $ox = -$dy * $scale;
                    $oy = $dx * $scale;

                    $outer_polygon[] = $ox + $polygon[$i + 0];
                    $outer_polygon[] = $oy + $polygon[$i + 1];
                    $outer_polygon[] = $ox + $polygon[$i + 2];
                    $outer_polygon[] = $oy + $polygon[$i + 3];
                }
            }

            $inner_polygon_total_points = count($inner_polygon);

            for ($i = 0; $i < $inner_polygon_total_points; $i += 2) {
                if (array_key_exists($i + 2, $inner_polygon) && array_key_exists($i + 3, $inner_polygon)) {
                    $polygon_border_segments[] = [
                        $inner_polygon[$i + 0],
                        $inner_polygon[$i + 1],
                        $outer_polygon[$i + 0],
                        $outer_polygon[$i + 1],
                        $outer_polygon[$i + 2],
                        $outer_polygon[$i + 3],
                        $inner_polygon[$i + 2],
                        $inner_polygon[$i + 3],
                    ];
                }
            }
        }

        return [$polygon, $polygon_border_segments];
    }
}
