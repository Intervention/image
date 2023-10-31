<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class ColorizeModifier implements ModifierInterface
{
    public function __construct(
        protected int $red = 0,
        protected int $green = 0,
        protected int $blue = 0
    ) {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        // normalize colorize levels
        $red = $this->normalizeLevel($this->red);
        $green = $this->normalizeLevel($this->green);
        $blue = $this->normalizeLevel($this->blue);

        foreach ($image as $frame) {
            $qrange = $frame->core()->getQuantumRange();
            $frame->core()->levelImage(0, $red, $qrange['quantumRangeLong'], Imagick::CHANNEL_RED);
            $frame->core()->levelImage(0, $green, $qrange['quantumRangeLong'], Imagick::CHANNEL_GREEN);
            $frame->core()->levelImage(0, $blue, $qrange['quantumRangeLong'], Imagick::CHANNEL_BLUE);
        }

        return $image;
    }

    private function normalizeLevel(int $level): int
    {
        return $level > 0 ? intval(round($level / 5)) : intval(round(($level + 100) / 100));
    }
}
