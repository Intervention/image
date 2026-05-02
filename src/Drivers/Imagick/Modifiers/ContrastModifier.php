<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use ImagickException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\ContrastModifier as GenericContrastModifier;

class ContrastModifier extends GenericContrastModifier implements SpecializedInterface
{
    /**
     * @throws ModifierException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        // sigmoidalContrastImage's midpoint argument is in QuantumRange units,
        // not the [0..1] range a normalized API would imply. Passing 0 pivots
        // the sigmoidal curve around pure black, which lifts every pixel
        // including the midtone — the opposite of a symmetric contrast
        // adjustment. Pivot around the middle of the QuantumRange so a
        // mid-grey pixel survives unchanged and the curve is symmetric.
        $midpoint = Imagick::QUANTUM_RANGE / 2;

        foreach ($image as $frame) {
            try {
                $result = $frame->native()->sigmoidalContrastImage($this->level > 0, abs($this->level / 4), $midpoint);
                if ($result === false) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to adjust image contrast',
                    );
                }
            } catch (ImagickException $e) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to adjust image contrast',
                    previous: $e
                );
            }
        }

        return $image;
    }
}
