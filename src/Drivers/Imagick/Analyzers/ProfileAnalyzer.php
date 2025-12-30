<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Analyzers;

use Intervention\Image\Analyzers\ProfileAnalyzer as GenericProfileAnalyzer;
use Intervention\Image\Colors\Profile;
use Intervention\Image\Exceptions\AnalyzerException;
use Intervention\Image\Exceptions\FilePointerException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

class ProfileAnalyzer extends GenericProfileAnalyzer implements SpecializedInterface
{
    /**
     * @throws InvalidArgumentException
     * @throws FilePointerException
     * @throws AnalyzerException
     */
    public function analyze(ImageInterface $image): mixed
    {
        $profiles = $image->core()->native()->getImageProfiles('icc');

        if (!array_key_exists('icc', $profiles)) {
            throw new AnalyzerException('No ICC profile found in image');
        }

        return new Profile($profiles['icc']);
    }
}
