<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Analyzers;

use GdImage;
use Intervention\Image\Analyzers\PixelColorAnalyzer as GenericPixelColorAnalyzer;
use Intervention\Image\Exceptions\AnalyzerException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\StateException;
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
     *
     * @throws InvalidArgumentException
     * @throws AnalyzerException
     * @throws StateException
     */
    public function analyze(ImageInterface $image): mixed
    {
        return $this->colorAt(
            $image->colorspace(),
            $image->core()->frame($this->frame)->native()
        );
    }

    /**
     * @throws InvalidArgumentException
     * @throws AnalyzerException
     * @throws StateException
     */
    protected function colorAt(ColorspaceInterface $colorspace, GdImage $gd): ColorInterface
    {
        $index = @imagecolorat($gd, $this->x, $this->y);

        if (!is_int($index)) {
            throw new InvalidArgumentException(
                'The specified position (' . $this->x . ', ' . $this->y . ') is not within the image area',
            );
        }

        try {
            $index = imagecolorsforindex($gd, $index);
        } catch (ValueError) {
            throw new AnalyzerException(
                'The specified index is outside of the range',
            );
        }

        return $this->driver()->colorProcessor($colorspace)->nativeToColor($index);
    }
}
