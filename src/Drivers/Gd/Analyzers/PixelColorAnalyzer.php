<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Analyzers;

use Intervention\Image\Analyzers\PixelColorAnalyzer as GenericPixelColorAnalyzer;
use Intervention\Image\Exceptions\AnalyzerException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorProcessorInterface;
use Intervention\Image\Interfaces\FrameInterface;
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
        $colorProcessor = $this->driver()->colorProcessor($image);

        return $this->colorAt($colorProcessor, $image->core()->frame($this->frame));
    }

    protected function colorAt(ColorProcessorInterface $processor, FrameInterface $frame): ColorInterface
    {
        $gd = $frame->native();
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

        return $processor->nativeToColor($index);
    }
}
