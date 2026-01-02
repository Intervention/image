<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use ImagickException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\InsertModifier as GenericInsertModifier;

class InsertModifier extends GenericInsertModifier implements SpecializedInterface
{
    /**
     * @throws ModifierException
     * @throws StateException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $watermark = $this->driver()->handleImageInput($this->element);
        $position = $this->position($image, $watermark);

        // set opacity of watermark
        if ($this->opacity < 100) {
            try {
                $result = $watermark->core()->native()->setImageAlphaChannel(Imagick::ALPHACHANNEL_SET)
                    && $watermark->core()->native()->evaluateImage(
                        Imagick::EVALUATE_DIVIDE,
                        $this->opacity > 0 ? 100 / $this->opacity : 1000,
                        Imagick::CHANNEL_ALPHA,
                    );

                if ($result === false) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to set opacity of watermark',
                    );
                }
            } catch (ImagickException $e) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to set opacity of watermark',
                    previous: $e
                );
            }
        }

        foreach ($image as $frame) {
            try {
                $result = $frame->native()->compositeImage(
                    $watermark->core()->native(),
                    Imagick::COMPOSITE_DEFAULT,
                    $position->x(),
                    $position->y()
                );
                if ($result === false) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to insert watermark image',
                    );
                }
            } catch (ImagickException $e) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to insert watermark image',
                    previous: $e
                );
            }
        }

        return $image;
    }
}
