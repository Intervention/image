<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\DrawBezierModifier as GenericDrawBezierModifier;

class DrawBezierModifier extends GenericDrawBezierModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     *
     * @throws InvalidArgumentException
     * @throws ModifierException
     * @throws StateException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            if ($this->drawable->count() !== 3 && $this->drawable->count() !== 4) {
                throw new InvalidArgumentException('You must specify either 3 or 4 points to create a bezier curve');
            }

            [$polygon, $polygonBorderSegments] = $this->calculateBezierPoints();

            if ($this->drawable->hasBackgroundColor() || $this->drawable->hasBorder()) {
                $result = imagealphablending($frame->native(), true);
                $this->abortUnless($result, 'Unable to set alpha blending');

                $result = imageantialias($frame->native(), true);
                $this->abortUnless($result, 'Unable to set image antialias option');
            }

            if ($this->drawable->hasBackgroundColor()) {
                $backgroundColor = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
                    $this->backgroundColor()
                );

                $result = imagesetthickness($frame->native(), 0);
                $this->abortUnless($result, 'Unable to set line thickness');

                $result = imagefilledpolygon(
                    $frame->native(),
                    $polygon,
                    $backgroundColor
                );

                $this->abortUnless($result, 'Unable to draw line on image');
            }

            if ($this->drawable->hasBorder() && $this->drawable->borderSize() > 0) {
                $borderColor = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
                    $this->borderColor()
                );

                if ($this->drawable->borderSize() === 1) {
                    $result = imagesetthickness($frame->native(), $this->drawable->borderSize());
                    $this->abortUnless($result, 'Unable to set line thickness');

                    $count = count($polygon);
                    for ($i = 0; $i < $count; $i += 2) {
                        if (array_key_exists($i + 2, $polygon) && array_key_exists($i + 3, $polygon)) {
                            $result = imageline(
                                $frame->native(),
                                $polygon[$i],
                                $polygon[$i + 1],
                                $polygon[$i + 2],
                                $polygon[$i + 3],
                                $borderColor
                            );

                            $this->abortUnless($result, 'Unable to draw line on image');
                        }
                    }
                } else {
                    $polygonBorderSegmentsTotal = count($polygonBorderSegments);

                    for ($i = 0; $i < $polygonBorderSegmentsTotal; $i += 1) {
                        $result = imagefilledpolygon(
                            $frame->native(),
                            $polygonBorderSegments[$i],
                            $borderColor
                        );

                        $this->abortUnless($result, 'Unable to draw line on image');
                    }
                }
            }
        }

        return $image;
    }

    /**
     * Calculate interpolation points for quadratic beziers using the Bernstein polynomial form
     *
     * @return array{'x': float, 'y': float}
     */
    private function calculateQuadraticBezierInterpolationPoint(float $t = 0.05): array
    {
        $remainder = 1 - $t;
        $controlPoint1Multiplier = $remainder * $remainder;
        $controlPoint2Multiplier = $remainder * $t * 2;
        $controlPoint3Multiplier = $t * $t;

        $x = (
            $this->drawable->first()->x() * $controlPoint1Multiplier +
            $this->drawable->second()->x() * $controlPoint2Multiplier +
            $this->drawable->last()->x() * $controlPoint3Multiplier
        );
        $y = (
            $this->drawable->first()->y() * $controlPoint1Multiplier +
            $this->drawable->second()->y() * $controlPoint2Multiplier +
            $this->drawable->last()->y() * $controlPoint3Multiplier
        );

        return ['x' => $x, 'y' => $y];
    }

    /**
     * Calculate interpolation points for cubic beziers using the Bernstein polynomial form
     *
     * @return array{'x': float, 'y': float}
     */
    private function calculateCubicBezierInterpolationPoint(float $t = 0.05): array
    {
        $remainder = 1 - $t;
        $tSquared = $t * $t;
        $remainderSquared = $remainder * $remainder;
        $controlPoint1Multiplier = $remainderSquared * $remainder;
        $controlPoint2Multiplier = $remainderSquared * $t * 3;
        $controlPoint3Multiplier = $tSquared * $remainder * 3;
        $controlPoint4Multiplier = $tSquared * $t;

        $x = (
            $this->drawable->first()->x() * $controlPoint1Multiplier +
            $this->drawable->second()->x() * $controlPoint2Multiplier +
            $this->drawable->third()->x() * $controlPoint3Multiplier +
            $this->drawable->last()->x() * $controlPoint4Multiplier
        );
        $y = (
            $this->drawable->first()->y() * $controlPoint1Multiplier +
            $this->drawable->second()->y() * $controlPoint2Multiplier +
            $this->drawable->third()->y() * $controlPoint3Multiplier +
            $this->drawable->last()->y() * $controlPoint4Multiplier
        );

        return ['x' => $x, 'y' => $y];
    }

    /**
     * Calculate the points needed to draw a quadratic or cubic bezier with optional border/stroke
     *
     * @throws InvalidArgumentException
     * @return array{0: array<mixed>, 1: array<mixed>}
     */
    private function calculateBezierPoints(): array
    {
        if ($this->drawable->count() !== 3 && $this->drawable->count() !== 4) {
            throw new InvalidArgumentException('You must specify either 3 or 4 points to create a bezier curve');
        }

        $polygon = [];
        $innerPolygon = [];
        $outerPolygon = [];
        $polygonBorderSegments = [];

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
            $polygonTotalPoints = count($polygon);
            $offset = ($this->drawable->borderSize() / 2);

            for ($i = 0; $i < $polygonTotalPoints; $i += 2) {
                if (array_key_exists($i + 2, $polygon) && array_key_exists($i + 3, $polygon)) {
                    $dx = $polygon[$i + 2] - $polygon[$i];
                    $dy = $polygon[$i + 3] - $polygon[$i + 1];
                    $dxySqrt = ($dx * $dx + $dy * $dy) ** 0.5;

                    // inner polygon
                    $scale = $offset / $dxySqrt;
                    $ox = -$dy * $scale;
                    $oy = $dx * $scale;

                    $innerPolygon[] = $ox + $polygon[$i];
                    $innerPolygon[] = $oy + $polygon[$i + 1];
                    $innerPolygon[] = $ox + $polygon[$i + 2];
                    $innerPolygon[] = $oy + $polygon[$i + 3];

                    // outer polygon
                    $scale = -$offset / $dxySqrt;
                    $ox = -$dy * $scale;
                    $oy = $dx * $scale;

                    $outerPolygon[] = $ox + $polygon[$i];
                    $outerPolygon[] = $oy + $polygon[$i + 1];
                    $outerPolygon[] = $ox + $polygon[$i + 2];
                    $outerPolygon[] = $oy + $polygon[$i + 3];
                }
            }

            $innerPolygonTotalPoints = count($innerPolygon);

            for ($i = 0; $i < $innerPolygonTotalPoints; $i += 2) {
                if (array_key_exists($i + 2, $innerPolygon) && array_key_exists($i + 3, $innerPolygon)) {
                    $polygonBorderSegments[] = [
                        $innerPolygon[$i],
                        $innerPolygon[$i + 1],
                        $outerPolygon[$i],
                        $outerPolygon[$i + 1],
                        $outerPolygon[$i + 2],
                        $outerPolygon[$i + 3],
                        $innerPolygon[$i + 2],
                        $innerPolygon[$i + 3],
                    ];
                }
            }
        }

        return [$polygon, $polygonBorderSegments];
    }

    /**
     * Throw ModifierException with given message if result is 'false'
     *
     * @throws ModifierException
     */
    private function abortUnless(mixed $result, string $message): void
    {
        if ($result === false) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', ' . $message
            );
        }
    }
}
