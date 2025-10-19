<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Analyzers;

use Intervention\Image\Analyzers\ResolutionAnalyzer as GenericResolutionAnalyzer;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Exceptions\InputException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Origin;
use Intervention\Image\Resolution;
use Intervention\Image\Traits\CanBuildFilePointer;
use Throwable;

class ResolutionAnalyzer extends GenericResolutionAnalyzer implements SpecializedInterface
{
    use CanBuildFilePointer;

    /**
     * {@inheritdoc}
     *
     * @see AnalyzerInterface::analyze()
     */
    public function analyze(ImageInterface $image): mixed
    {
        $result = imageresolution($image->core()->native());

        if (!is_array($result)) {
            throw new RuntimeException('Unable to read image resolution');
        }

        // if GD's default resolution is returned I try to find resolution in origin
        if ($result[0] == 96 && $result[1] == 96 && $image->core()->resolutionChanged === false) {
            try {
                $alternativeResoltion = $this->readResolutionFromOrigin($image->origin());
            } catch (Throwable) {
                $alternativeResoltion = [96, 96];
            }

            $result = $alternativeResoltion !== $result ? $alternativeResoltion : $result;
        }

        return new Resolution(...$result);
    }

    /**
     * @throws DecoderException
     * @throws InputException
     * @throws RuntimeException
     * @return array<float>
     */
    private function readResolutionFromOrigin(Origin $origin): array
    {
        $handle = $this->buildFilePointerOrFail(file_get_contents($origin->filePath()));

        try {
            return $this->resolutionFromJfifHeader($handle);
        } catch (Throwable) {
            # code ...
        }

        try {
            return $this->resolutionFromExifHeader($handle);
        } catch (Throwable) {
            # code ...
        }

        try {
            return $this->resolutionFromPngPhys($handle);
        } catch (Throwable) {
            # code ...
        }

        throw new DecoderException('Unable to read resolution from path');
    }

    /**
     * @param resource $handle
     * @throws DecoderException
     * @throws InputException
     * @return array<float>
     */
    private function resolutionFromJfifHeader($handle): array
    {
        // read first 20 bytes
        rewind($handle);
        $header = fread($handle, 20);

        // find the JFIF segment
        $offset = strpos($header, 'JFIF');
        if ($offset === false) {
            throw new DecoderException('Unable to read JFIF header');
        }

        // read bytes at known offsets relative to JFIF
        $units = ord($header[$offset + 7]);
        $x = unpack('n', substr($header, $offset + 8, 2))[1];
        $y = unpack('n', substr($header, $offset + 10, 2))[1];

        fclose($handle);

        if ($units == 1) { // DPI
            return [$x, $y];
        } elseif ($units == 2) { // dots per cm → convert to DPI
            return [round($x * 2.54), round($y * 2.54)];
        } else { // no units
            return [$x, $y];
        }
    }

    /**
     * @param resource $handle
     * @throws DecoderException
     * @throws InputException
     * @throws NotSupportedException
     * @return array<float>
     */
    private function resolutionFromExifHeader($handle): array
    {
        if (!function_exists('exif_read_data')) {
            throw new NotSupportedException('Unable to read exif data');
        }

        rewind($handle);
        $data = @exif_read_data($handle, null, true);

        if ($data === false) {
            throw new DecoderException('Unable to read exif data');
        }

        if (isset($data['XResolution']) && isset($data['YResolution'])) {
            $resolution = [$data['XResolution'], $data['YResolution']];
        }

        if (isset($data['IFD0']) && isset($data['IFD0']['XResolution']) && isset($data['IFD0']['YResolution'])) {
            $resolution = [$data['IFD0']['XResolution'], $data['IFD0']['YResolution']];
        }

        if (!isset($resolution)) {
            throw new DecoderException('Unable to read exif data');
        }

        return array_map(function (mixed $value): int|float {
            if (strpos($value, '/') === false) {
                return $value;
            }

            $values = array_map(fn(string $value): int => intval($value), explode('/', $value));

            return $values[0] / $values[1];
        }, $resolution);
    }

    /**
     * @param resource $handle
     * @throws DecoderException
     * @throws InputException
     * @return array<float>
     */
    private function resolutionFromPngPhys($handle): array
    {
        rewind($handle);
        $signature = fread($handle, 8);

        // no PNG content
        if ($signature !== "\x89PNG\x0D\x0A\x1A\x0A") {
            fclose($handle);
            throw new InputException('No PNG format');
        }

        $marker = '';

        while (!feof($handle)) {
            $marker = strlen($marker) < 4 ? $marker . fread($handle, 1) : substr($marker, 1) . fread($handle, 1);

            // find pHYs chunk
            if ($marker === 'pHYs') {
                // find length
                fseek($handle, -8, SEEK_CUR);
                $length = fread($handle, 4);
                $length = unpack('N', $length)[1];
                fseek($handle, 4, SEEK_CUR);

                // read data
                $data = fread($handle, $length);

                $x = unpack('N', substr($data, 0, 4))[1];
                $y = unpack('N', substr($data, 4, 4))[1];

                fclose($handle);

                return [
                    round($x * .0254),
                    round($y * .0254),
                ];
            }
        }

        return [0, 0];
    }
}
