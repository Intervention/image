<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use GdImage;
use Intervention\Image\Exceptions\ColorDecoderException;
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
     * @throws ColorDecoderException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $this->validatePointCount();

        [$polygon, $polygonBorderSegments] = $this->calculateBezierPoints();

        foreach ($image as $frame) {
            if ($this->drawable->hasBackgroundColor() || $this->drawable->hasBorder()) {
                $this->abortUnless(imagealphablending($frame->native(), true), 'Unable to set alpha blending');
                $this->abortUnless(imageantialias($frame->native(), true), 'Unable to set image antialias option');
            }

            if ($this->drawable->hasBackgroundColor()) {
                $backgroundColor = $this->driver()->colorProcessor($image)->export($this->backgroundColor());
                $this->drawBezierBackground($frame->native(), $polygon, $backgroundColor);
            }

            if ($this->drawable->hasBorder() && $this->drawable->borderSize() > 0) {
                $borderColor = $this->driver()->colorProcessor($image)->export($this->borderColor());
                $this->drawBezierBorder($frame->native(), $polygon, $polygonBorderSegments, $borderColor);
            }
        }

        return $image;
    }

    /**
     * Validate that the drawable has exactly 3 or 4 points.
     *
     * @throws InvalidArgumentException
     */
    private function validatePointCount(): void
    {
        if ($this->drawable->count() !== 3 && $this->drawable->count() !== 4) {
            throw new InvalidArgumentException('You must specify either 3 or 4 points to create a bezier curve');
        }
    }

    /**
     * Draw the bezier polygon with the background color.
     *
     * @param array<mixed> $polygon
     * @throws ModifierException
     */
    private function drawBezierBackground(GdImage $canvas, array $polygon, int $color): void
    {
        $this->abortUnless(imagesetthickness($canvas, 0), 'Unable to set line thickness');
        $this->abortUnless(imagefilledpolygon($canvas, $polygon, $color), 'Unable to draw line on image');
    }

    /**
     * Draw the bezier border, using thin lines for size 1 or filled polygon segments otherwise
     *
     * @param array<mixed> $polygon
     * @param array<mixed> $polygonBorderSegments
     * @throws ModifierException
     */
    private function drawBezierBorder(
        GdImage $canvas,
        array $polygon,
        array $polygonBorderSegments,
        int $borderColor,
    ): void {
        if ($this->drawable->borderSize() === 1) {
            $this->drawThinBorder($canvas, $polygon, $borderColor);
        } else {
            $this->drawThickBorder($canvas, $polygonBorderSegments, $borderColor);
        }
    }

    /**
     * Draw a 1px border by connecting each consecutive polygon point pair with a line.
     *
     * @param array<mixed> $polygon
     * @throws ModifierException
     */
    private function drawThinBorder(GdImage $canvas, array $polygon, int $borderColor): void
    {
        $this->abortUnless(imagesetthickness($canvas, $this->drawable->borderSize()), 'Unable to set line thickness');

        $count = count($polygon);
        for ($i = 0; $i < $count; $i += 2) {
            if (!array_key_exists($i + 2, $polygon) || !array_key_exists($i + 3, $polygon)) {
                continue;
            }

            $this->abortUnless(
                imageline($canvas, $polygon[$i], $polygon[$i + 1], $polygon[$i + 2], $polygon[$i + 3], $borderColor),
                'Unable to draw line on image'
            );
        }
    }

    /**
     * Draw a thick border by filling each pre-computed border segment polygon.
     *
     * @param array<mixed> $polygonBorderSegments
     * @throws ModifierException
     */
    private function drawThickBorder(GdImage $canvas, array $polygonBorderSegments, int $borderColor): void
    {
        foreach ($polygonBorderSegments as $segment) {
            $this->abortUnless(
                imagefilledpolygon($canvas, $segment, $borderColor),
                'Unable to draw line on image'
            );
        }
    }

    /**
     * Calculate interpolation points for quadratic beziers using the Bernstein polynomial form.
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
     * Calculate interpolation points for cubic beziers using the Bernstein polynomial form.
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
     * Calculate the points needed to draw a quadratic or cubic bezier with optional border/stroke.
     *
     * @throws InvalidArgumentException
     * @throws ModifierException
     * @return array{0: array<mixed>, 1: array<mixed>}
     */
    private function calculateBezierPoints(): array
    {
        $polygon = [];
        $polygonBorderSegments = [];

        $t = 0.05;

        $polygon[] = $this->drawable->first()->x();
        $polygon[] = $this->drawable->first()->y();

        for ($i = $t; $i < 1; $i += $t) {
            ['x' => $x, 'y' => $y] = $this->calculateBezierInterpolationPoint($i);
            $polygon[] = (int) $x;
            $polygon[] = (int) $y;
        }

        $polygon[] = $this->drawable->last()->x();
        $polygon[] = $this->drawable->last()->y();

        if ($this->drawable->hasBorder() && $this->drawable->borderSize() > 1) {
            $polygonBorderSegments = $this->calculateBorderSegments($polygon);
        }

        return [$polygon, $polygonBorderSegments];
    }

    /**
     * Dispatch to the correct interpolation method based on point count.
     *
     * @return array{'x': float, 'y': float}
     */
    private function calculateBezierInterpolationPoint(float $t): array
    {
        if ($this->drawable->count() === 3) {
            return $this->calculateQuadraticBezierInterpolationPoint($t);
        }

        return $this->calculateCubicBezierInterpolationPoint($t);
    }

    /**
     * Build the inner/outer offset polygons and stitch them into border segments.
     *
     * @param array<mixed> $polygon
     * @throws ModifierException
     * @return array<mixed>
     */
    private function calculateBorderSegments(array $polygon): array
    {
        $innerPolygon = [];
        $outerPolygon = [];
        $offset = $this->drawable->borderSize() / 2;
        $total = count($polygon);

        for ($i = 0; $i < $total; $i += 2) {
            if (!array_key_exists($i + 2, $polygon) || !array_key_exists($i + 3, $polygon)) {
                continue;
            }

            $dx = $polygon[$i + 2] - $polygon[$i];
            $dy = $polygon[$i + 3] - $polygon[$i + 1];
            $dxySqrt = sqrt($dx * $dx + $dy * $dy);

            if ($dxySqrt === 0.0) {
                throw new ModifierException('Failed to apply ' . self::class . ', division by zero');
            }

            $scale = $offset / $dxySqrt;
            $ox = -$dy * $scale;
            $oy = $dx * $scale;

            $innerPolygon[] = $ox + $polygon[$i];
            $innerPolygon[] = $oy + $polygon[$i + 1];
            $innerPolygon[] = $ox + $polygon[$i + 2];
            $innerPolygon[] = $oy + $polygon[$i + 3];

            $scale = -$offset / $dxySqrt;
            $ox = -$dy * $scale;
            $oy = $dx * $scale;

            $outerPolygon[] = $ox + $polygon[$i];
            $outerPolygon[] = $oy + $polygon[$i + 1];
            $outerPolygon[] = $ox + $polygon[$i + 2];
            $outerPolygon[] = $oy + $polygon[$i + 3];
        }

        return $this->stitchBorderSegments($innerPolygon, $outerPolygon);
    }

    /**
     * Stitch inner and outer polygon point arrays into 4-corner segment quads.
     *
     * @param array<mixed> $innerPolygon
     * @param array<mixed> $outerPolygon
     * @return array<mixed>
     */
    private function stitchBorderSegments(array $innerPolygon, array $outerPolygon): array
    {
        $segments = [];
        $total = count($innerPolygon);

        for ($i = 0; $i < $total; $i += 2) {
            if (!array_key_exists($i + 2, $innerPolygon) || !array_key_exists($i + 3, $innerPolygon)) {
                continue;
            }

            $segments[] = [
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

        return $segments;
    }
}
