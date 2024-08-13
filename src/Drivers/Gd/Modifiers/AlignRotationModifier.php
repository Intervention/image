<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\AlignRotationModifier as GenericAlignRotationModifier;

class AlignRotationModifier extends GenericAlignRotationModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $image = match ($image->exif('IFD0.Orientation')) {
            2 => $image->flop(),
            3 => $image->rotate(180),
            4 => $image->rotate(180)->flop(),
            5 => $image->rotate(270)->flop(),
            6 => $image->rotate(270),
            7 => $image->rotate(90)->flop(),
            8 => $image->rotate(90),
            default => $image
        };

        return $this->markAligned($image);
    }

    /**
     * Set exif data of image to top-left orientation, marking the image as
     * aligned and making sure the rotation correction process is not
     * performed again.
     *
     * @param ImageInterface $image
     * @return ImageInterface
     */
    private function markAligned(ImageInterface $image): ImageInterface
    {
        $exif = $image->exif()->map(function ($item) {
            if (is_array($item) && array_key_exists('Orientation', $item)) {
                $item['Orientation'] = 1;
                return $item;
            }

            return $item;
        });

        return $image->setExif($exif);
    }
}
