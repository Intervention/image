<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use ImagickException;
use Intervention\Image\Collection;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Interfaces\FrameInterface;
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
        foreach ($image as $frame) {
            $profiles = $this->frameProfilesToKeep($frame);
            $this->stripFrame($frame);
            $this->reApplyProfiles($frame, $profiles);
        }

        $image->setExif(new Collection());

        return $image;
    }

    /**
     * Strip meta data from frame.
     *
     * stripImage() leaves the meta data in the property cache and
     * instead sets a "png:exclude-chunk" artifact to keep the png
     * encoder from writing it back. That artifact also discards the
     * icc profile, because the png encoder skips every profile as
     * soon as the text chunks are excluded. We drop the artifact and
     * clear the property cache instead, so that the meta data is
     * really gone for every encoder and the icc profile can be
     * restored.
     *
     * @throws ModifierException
     */
    private function stripFrame(FrameInterface $frame): void
    {
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
    }

    /**
     * Return array of frame profiles to be re-applied after the stripping process.
     *
     * Currently the following profiles are preserved
     *
     * - ICC profile
     * - Ultra HDR gain map
     *
     * @throws ModifierException
     * @return array<mixed>
     */
    private function frameProfilesToKeep(FrameInterface $frame): array
    {
        $preserve = ['icc', 'hdrgm'];
        $profiles = [];

        foreach ($preserve as $key) {
            try {
                $result = $frame->native()->getImageProfiles($key);
                if (array_key_exists($key, $result)) {
                    $profiles[$key] = $result[$key];
                }
            } catch (ImagickException $e) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to preserve ' . $key . ' profiles',
                    previous: $e,
                );
            }
        }


        return $profiles;
    }

    /**
     * Re-apply given profiles to the frame.
     *
     * @param array<mixed> $profiles
     * @throws ModifierException
     */
    private function reApplyProfiles(FrameInterface $frame, array $profiles): void
    {
        foreach ($profiles as $key => $profile) {
            try {
                $result = $frame->native()->profileImage($key, $profile);
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

    /**
     * Remove all meta data properties of the given image.
     *
     * @throws ImagickException
     */
    private function clearProperties(Imagick $imagick): void
    {
        $deleteProperty = function (Imagick $imagick, string $property): void {
            if (
                str_starts_with($property, 'png:')
                || str_starts_with($property, 'jpeg:')
                || str_starts_with($property, 'hdrgm:')
            ) {
                return;
            }

            $imagick->deleteImageProperty($property);
        };

        foreach ($imagick->getImageProperties('*', false) as $property) {
            $deleteProperty($imagick, $property);
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
                $deleteProperty($imagick, substr($line, 0, $position));
            }
        }
    }
}
