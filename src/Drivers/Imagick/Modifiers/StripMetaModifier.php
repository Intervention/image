<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use ImagickException;
use Intervention\Image\Collection;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

class StripMetaModifier implements ModifierInterface, SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see Intervention\Image\Interfaces\ModifierInterface::apply()
     *
     * @throws ModifierException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        // getImageProfiles(), stripImage() and profileImage() all act on the
        // frame the iterator is currently pointing at, so every frame has to
        // be stripped individually. Otherwise the meta data of an animated
        // image survives on every frame but one.
        foreach ($image as $frame) {
            // preserve icc profiles
            try {
                $profiles = $frame->native()->getImageProfiles('icc');
            } catch (ImagickException $e) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to preserve icc profiles',
                    previous: $e,
                );
            }

            // remove meta data
            try {
                $result = $frame->native()->stripImage();
                if ($result === false) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to strip meta data',
                    );
                }
            } catch (ImagickException $e) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to strip meta data',
                    previous: $e,
                );
            }

            // stripImage() leaves the meta data in the property cache and
            // instead sets a "png:exclude-chunk" artifact to keep the png
            // encoder from writing it back. That artifact also discards the
            // icc profile, because the png encoder skips every profile as
            // soon as the text chunks are excluded. Drop the artifact and
            // clear the property cache instead, so that the meta data is
            // really gone for every encoder and the icc profile can be
            // restored below.
            try {
                if ($frame->native()->getImageArtifact('png:exclude-chunk') !== null) {
                    $frame->native()->deleteImageArtifact('png:exclude-chunk');
                }

                $this->clearProperties($frame->native());
            } catch (ImagickException $e) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to clear image properties',
                    previous: $e,
                );
            }

            if ($profiles !== []) {
                // re-apply icc profiles
                try {
                    $result = $frame->native()->profileImage("icc", $profiles['icc']);
                    if ($result === false) {
                        throw new ModifierException(
                            'Failed to apply ' . self::class . ', unable to re-apply icc profile',
                        );
                    }
                } catch (ImagickException $e) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to re-apply icc profile',
                        previous: $e,
                    );
                }
            }
        }

        $image->setExif(new Collection());

        return $image;
    }

    /**
     * Remove all meta data properties of the given image.
     *
     * @throws ImagickException
     */
    private function clearProperties(Imagick $imagick): void
    {
        foreach ($imagick->getImageProperties('*', false) as $property) {
            $this->deleteProperty($imagick, $property);
        }

        // getImageProperties() silently skips every property whose name starts
        // with "[", which a png text chunk is able to produce. The png encoder
        // does not skip them, so the ones that are left are read again through
        // the "%[*]" format. That format builds a string of every property and
        // its value, which is why it only runs once the properties above are
        // gone and there is almost never anything left to report.
        foreach (explode("\n", (string) $imagick->identifyFormat('%[*]')) as $line) {
            $position = strpos($line, '=');
            if ($position !== false && $position > 0) {
                $this->deleteProperty($imagick, substr($line, 0, $position));
            }
        }
    }

    /**
     * Remove the given property of the given image unless it is an encoder hint.
     *
     * @throws ImagickException
     */
    private function deleteProperty(Imagick $imagick, string $property): void
    {
        // the png encoder skips "png:" and "jpeg:" properties, they carry
        // encoding hints like the jpeg sampling factor instead of meta data
        if (str_starts_with($property, 'png:') || str_starts_with($property, 'jpeg:')) {
            return;
        }

        $imagick->deleteImageProperty($property);
    }
}
