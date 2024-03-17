<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Analyzers;

use Intervention\Image\Analyzers\ProfileAnalyzer as GenericProfileAnalyzer;
use Intervention\Image\Colors\Profile;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

class ProfileAnalyzer extends GenericProfileAnalyzer implements SpecializedInterface
{
    public function analyze(ImageInterface $image): mixed
    {
        $profiles = $image->core()->native()->getImageProfiles('icc');

        if (!array_key_exists('icc', $profiles)) {
            throw new ColorException('No ICC profile found in image.');
        }

        return new Profile($profiles['icc']);
    }
}
