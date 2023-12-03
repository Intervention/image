<?php

namespace Intervention\Image\Drivers\Imagick\Analyzers;

use Intervention\Image\Colors\Profile;
use Intervention\Image\Drivers\DriverSpecializedAnalyzer;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Interfaces\ImageInterface;

class ProfileAnalyzer extends DriverSpecializedAnalyzer
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
