<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\TrimModifier as GenericTrimModifier;

class TrimModifier extends GenericTrimModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        if ($image->isAnimated()) {
            throw new NotSupportedException('Trim modifier cannot be applied to animated images.');
        }

        $imagick = $image->core()->native();
        $imagick->trimImage(($this->tolerance / 100 * $imagick->getQuantum()) / 1.5);
        $imagick->setImagePage(0, 0, 0, 0);

        return $image;
    }
}
