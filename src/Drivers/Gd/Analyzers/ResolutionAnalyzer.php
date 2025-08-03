<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Analyzers;

use Intervention\Image\Analyzers\ResolutionAnalyzer as GenericResolutionAnalyzer;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Resolution;

class ResolutionAnalyzer extends GenericResolutionAnalyzer implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see AnalyzerInterface::analyze()
     */
    public function analyze(ImageInterface $image): mixed
    {
        $result = imageresolution($image->core()->native());

        if (!is_array($result)) {
            throw new RuntimeException('Unable to read image resolution.');
        }

        return new Resolution(...$result);
    }
}
