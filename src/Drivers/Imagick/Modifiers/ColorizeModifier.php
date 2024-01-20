<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use Intervention\Image\Drivers\DriverSpecialized;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

/**
 * @property int $red
 * @property int $green
 * @property int $blue
 */
class ColorizeModifier extends DriverSpecialized implements ModifierInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $red = $this->normalizeLevel($this->red);
        $green = $this->normalizeLevel($this->green);
        $blue = $this->normalizeLevel($this->blue);

        foreach ($image as $frame) {
            $qrange = $frame->native()->getQuantumRange();
            $frame->native()->levelImage(0, $red, $qrange['quantumRangeLong'], Imagick::CHANNEL_RED);
            $frame->native()->levelImage(0, $green, $qrange['quantumRangeLong'], Imagick::CHANNEL_GREEN);
            $frame->native()->levelImage(0, $blue, $qrange['quantumRangeLong'], Imagick::CHANNEL_BLUE);
        }

        return $image;
    }

    private function normalizeLevel(int $level): int
    {
        return $level > 0 ? intval(round($level / 5)) : intval(round(($level + 100) / 100));
    }
}
