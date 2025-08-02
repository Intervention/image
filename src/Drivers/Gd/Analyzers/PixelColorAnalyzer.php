<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Analyzers;

use GdImage;
use Intervention\Image\Analyzers\PixelColorAnalyzer as GenericPixelColorAnalyzer;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Exceptions\GeometryException;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use ValueError;

class PixelColorAnalyzer extends GenericPixelColorAnalyzer implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see AnalyzerInterface::analyze()
     */
    public function analyze(ImageInterface $image): mixed
    {
        return $this->colorAt(
            $image->colorspace(),
            $image->core()->frame($this->frame_key)->native()
        );
    }

    /**
     * @throws GeometryException
     * @throws ColorException
     * @throws RuntimeException
     */
    protected function colorAt(ColorspaceInterface $colorspace, GdImage $gd): ColorInterface
    {
        $index = @imagecolorat($gd, $this->x, $this->y);

        if ($index === false) {
            throw new RuntimeException('Unable to read color at pixel ' . $this->x . ', ' . $this->y . '.');
        }

        try {
            $index = imagecolorsforindex($gd, $index);
        } catch (ValueError) {
            throw new GeometryException(
                'The specified position is not in the valid image area.'
            );
        }

        return $this->driver()->colorProcessor($colorspace)->nativeToColor($index);
    }
}
