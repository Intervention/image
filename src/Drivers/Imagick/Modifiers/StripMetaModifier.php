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
     * Profiles to be re-applied after the stripping process.
     */
    private const array PRESERVED_PROFILE_NAMES = ['icc', 'hdrgm'];

    /**
     * Property prefixes to be preserved.
     */
    private const array PRESERVED_PROPERTY_PREFIXES = ['png:', 'jpeg:', 'hdrgm:'];

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
            $profiles = $this->collectPreservedProfiles($frame);
            $this->stripFrameAndPurgeProperties($frame);
            $this->restorePreservedProfiles($frame, $profiles);
        }

        $image->setExif(new Collection());

        return $image;
    }

    /**
     * Return array of frame profiles to be re-applied after the stripping process.
     *
     * @throws ModifierException
     * @return array<mixed>
     */
    private function collectPreservedProfiles(FrameInterface $frame): array
    {
        $imagick = $frame->native();
        $profiles = [];

        foreach (self::PRESERVED_PROFILE_NAMES as $key) {
            try {
                $result = $imagick->getImageProfiles($key);
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
    private function stripFrameAndPurgeProperties(FrameInterface $frame): void
    {
        $imagick = $frame->native();

        try {
            $result = $imagick->stripImage();
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
            $this->removeArtifactIfPresent($imagick, 'png:exclude-chunk');
            $this->purgeMetadataProperties($imagick);
        } catch (ImagickException $e) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to clear image properties',
                previous: $e,
            );
        }
    }

    /**
     * Re-apply given profiles to the frame.
     *
     * @param array<mixed> $profiles
     * @throws ModifierException
     */
    private function restorePreservedProfiles(FrameInterface $frame, array $profiles): void
    {
        $imagick = $frame->native();

        foreach ($profiles as $key => $profile) {
            try {
                $result = $imagick->profileImage($key, $profile);
                if ($result === false) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to re-apply ' . $key . ' profile',
                    );
                }
            } catch (ImagickException $e) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to re-apply ' . $key . ' profile',
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
    private function purgeMetadataProperties(Imagick $imagick): void
    {
        foreach ($this->propertyNames($imagick) as $property) {
            $this->deletePropertyUnlessPreserved($imagick, $property);
        }

        // getImageProperties() silently skips every property whose name starts
        // with "[", which a png text chunk is able to produce. The png encoder
        // does not skip them, so the ones that are left are read again through
        // the "%[*]" format. That format builds a string of every property and
        // its value, which is why it only runs once the properties above are
        // gone and there is almost never anything left to report.
        foreach ($this->propertyLines($imagick) as $line) {
            $property = $this->extractPropertyNameFromIdentifyLine($imagick, $line);
            if (is_string($property)) {
                $this->deletePropertyUnlessPreserved($imagick, $property);
            }
        }
    }

    /**
     * Delete given image artifact if it exists.
     *
     * @throws ImagickException
     */
    private function removeArtifactIfPresent(Imagick $imagick, string $artifact): void
    {
        // @phpstan-ignore notIdentical.alwaysTrue
        if ($imagick->getImageArtifact($artifact) !== null) {
            $imagick->deleteImageArtifact($artifact);
        }
    }

    /**
     * Return property names visible through getImageProperties().
     *
     * @throws ImagickException
     * @return array<string>
     */
    private function propertyNames(Imagick $imagick): array
    {
        return $imagick->getImageProperties('*', false);
    }

    /**
     * Return one "%[*]" entry per line.
     *
     * @throws ImagickException
     * @return array<string>
     */
    private function propertyLines(Imagick $imagick): array
    {
        $output = (string) $imagick->identifyFormat('%[*]');

        if ($output === '') {
            return [];
        }

        return array_filter(explode("\n", $output), static fn(string $line): bool => $line !== '');
    }

    /**
     * Delete image property unless it is an encoder hint that must survive
     * metadata stripping.
     *
     * @throws ImagickException
     */
    private function deletePropertyUnlessPreserved(Imagick $imagick, string $property): void
    {
        if ($this->isPreservedProperty($property)) {
            return;
        }

        $imagick->deleteImageProperty($property);
    }

    /**
     * Determine if given property should be preserved.
     */
    private function isPreservedProperty(string $property): bool
    {
        foreach (self::PRESERVED_PROPERTY_PREFIXES as $prefix) {
            if (str_starts_with($property, $prefix)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Resolve an identifyFormat("%[*]") line back to the property name.
     *
     * @throws ImagickException
     */
    private function extractPropertyNameFromIdentifyLine(Imagick $imagick, string $line): ?string
    {
        if ($line === '' || $line[0] === '=') {
            return null;
        }

        for ($position = strlen($line) - 1; $position > 0; $position--) {
            if ($line[$position] !== '=') {
                continue;
            }

            $property = substr($line, 0, $position);
            if ($this->lineMatchesProperty($imagick, $line, $property)) {
                return $property;
            }
        }

        return null;
    }

    /**
     * Check whether a "%[*]" output line belongs to the given property name.
     *
     * @throws ImagickException
     */
    private function lineMatchesProperty(Imagick $imagick, string $line, string $property): bool
    {
        $value = $imagick->getImageProperty($property);

        // @phpstan-ignore identical.alwaysFalse
        if ($value === false) {
            return false;
        }

        $serialized = $property . '=' . $value;

        if ($line === $serialized) {
            return true;
        }

        return $line === explode("\n", $serialized, 2)[0];
    }
}
