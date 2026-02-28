<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\BlurModifier as GenericBlurModifier;

class BlurModifier extends GenericBlurModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     *
     * @throws ModifierException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            for ($i = 0; $i < $this->level; $i++) {
                $result = imagefilter($frame->native(), IMG_FILTER_GAUSSIAN_BLUR);
                if ($result === false) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to process blur effect',
                    );
                }
            }
        }

        return $image;
    }
}
