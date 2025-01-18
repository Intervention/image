<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Collection;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

class StripMetaModifier implements ModifierInterface, SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see Intervention\Image\Interfaces\ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        // preserve icc profiles
        $profiles = $image->core()->native()->getImageProfiles('icc');

        // remove meta data
        $image->core()->native()->stripImage();
        $image->setExif(new Collection());

        if ($profiles !== []) {
            // re-apply icc profiles
            $image->core()->native()->profileImage("icc", $profiles['icc']);
        }
        return $image;
    }
}
