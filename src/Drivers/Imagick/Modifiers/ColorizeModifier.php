<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use Intervention\Image\Exceptions\ImageException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\ColorizeModifier as GenericColorizeModifier;

class ColorizeModifier extends GenericColorizeModifier implements SpecializedInterface
{
    /**
     * @throws ModifierException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $red = $this->normalizeLevel($this->red);
        $green = $this->normalizeLevel($this->green);
        $blue = $this->normalizeLevel($this->blue);

        foreach ($image as $frame) {
            try {
                $qrange = $frame->native()->getQuantumRange();
            } catch (ImageException $e) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to get quantum range',
                    previous: $e
                );
            }

            try {
                $result = $frame->native()->levelImage(0, $red, $qrange['quantumRangeLong'], Imagick::CHANNEL_RED)
                    && $frame->native()->levelImage(0, $green, $qrange['quantumRangeLong'], Imagick::CHANNEL_GREEN)
                    && $frame->native()->levelImage(0, $blue, $qrange['quantumRangeLong'], Imagick::CHANNEL_BLUE);

                if ($result === false) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to adjust image colors',
                    );
                }
            } catch (ImageException $e) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to adjust image colors',
                    previous: $e
                );
            }
        }

        return $image;
    }

    private function normalizeLevel(int $level): int
    {
        return $level > 0 ? intval(round($level / 5)) : intval(round(($level + 100) / 100));
    }
}
