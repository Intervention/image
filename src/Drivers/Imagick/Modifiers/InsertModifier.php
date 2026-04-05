<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use ImagickException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\InsertModifier as GenericInsertModifier;
use Intervention\Image\Traits\CanConvertRange;

class InsertModifier extends GenericInsertModifier implements SpecializedInterface
{
    use CanConvertRange;

    /**
     * @throws ModifierException
     * @throws StateException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $watermark = $this->driver()->decodeImage($this->image);
        $position = $this->position($image, $watermark);

        // set opacity of watermark
        if ($this->transparency < 1) {
            try {
                $opacity = (int) round(self::convertRange($this->transparency, 0, 1, 0, 100));
                $alphaEval = $opacity > 0 ? 100 / $opacity : 1000;

                $result = $watermark->core()->native()->setImageAlphaChannel(Imagick::ALPHACHANNEL_SET)
                    && $watermark->core()->native()->evaluateImage(
                        Imagick::EVALUATE_DIVIDE,
                        $alphaEval,
                        Imagick::CHANNEL_ALPHA,
                    );

                if ($result === false) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to set transparency of watermark',
                    );
                }
            } catch (ImagickException $e) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to set transparency of watermark',
                    previous: $e
                );
            } catch (RuntimeException $e) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to set transparency of watermark',
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
