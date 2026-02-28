<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\OrientModifier as GenericOrientModifier;

class OrientModifier extends GenericOrientModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $image = match ($this->orientation($image)) {
            2 => $image->flip(),
            3 => $image->rotate(180),
            4 => $image->rotate(180)->flip(),
            5 => $image->rotate(90)->flip(),
            6 => $image->rotate(90),
            7 => $image->rotate(270)->flip(),
            8 => $image->rotate(270),
            default => $image
        };

        return $this->markAligned($image);
    }

    /**
     * Return exif information about image orientation.
     */
    private function orientation(ImageInterface $image): int
    {
        $orientation = $image->exif('IFD0.Orientation');

        return is_numeric($orientation) ? (int) $orientation : 0;
    }

    /**
     * Set exif data of image to top-left orientation, marking the image as
     * aligned and making sure the rotation correction process is not
     * performed again.
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
