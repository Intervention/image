<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\AlignRotationModifier as GenericAlignRotationModifier;

class AlignRotationModifier extends GenericAlignRotationModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        return match ($image->exif('IFD0.Orientation')) {
            2 => $image->flop(),
            3 => $image->rotate(180),
            4 => $image->rotate(180)->flop(),
            5 => $image->rotate(270)->flop(),
            6 => $image->rotate(270),
            7 => $image->rotate(90)->flop(),
            8 => $image->rotate(90),
            default => $image
        };
    }
}
