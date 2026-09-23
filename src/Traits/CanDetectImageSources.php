<?php

declare(strict_types=1);

namespace Intervention\Image\Traits;

use Intervention\Image\Interfaces\DataUriInterface;
use Stringable;

trait CanDetectImageSources
{
    /**
     * Returns true if the specified content could be base64 encoded.
     *
     * This does not necessarily mean that the content actually meets this
     * assumption, but only serves as an initial filter.
     */
    protected function couldBeBase64Data(mixed $input): bool
    {
        if (!is_string($input) && !$input instanceof Stringable) {
            return false;
        }

        $input = (string) $input;

        if (str_ends_with($input, '=')) {
            return true;
        }

        $decoded = base64_decode($input, strict: true);

        if ($decoded === false) {
            return false;
        }

        return base64_encode($decoded) === $input;
    }

    /**
     * Returns true if the specified content could be binary data.
     *
     * This does not necessarily mean that the content actually meets this
     * assumption, but only serves as an initial filter.
     */
    protected function couldBeBinaryData(mixed $input): bool
    {
        if (!is_string($input) && !$input instanceof Stringable) {
            return false;
        }

        $input = (string) $input;

        if ($input === '') {
            return false;
        }

        // svg patterns are raw image data
        if (preg_match('/^(<\?xml[^>]*\?>.*)?<svg[^>]*>/is', $input) === 1) {
            return true;
        }

        // ASCII control bytes (except tab, LF, CR) are a strong binary signal
        if (preg_match('/[\x00-\x08\x0E-\x1F\x7F]/', $input) === 1) {
            return true;
        }

        // invalid UTF-8 byte sequences usually indicate raw binary content
        return preg_match('//u', $input) !== 1;
    }

    /**
     * Returns true if the specified content could be a data uri.
     *
     * This does not necessarily mean that the content actually meets this
     * assumption, but only serves as an initial filter.
     */
    protected function couldBeDataUrl(mixed $input): bool
    {
        if ($input instanceof DataUriInterface) {
            return true;
        }

        return is_string($input) && str_starts_with($input, 'data:');
    }

    /**
     * Returns true if the specified content could be a file path.
     *
     * This does not necessarily mean that the content actually meets this
     * assumption, but only serves as an initial filter.
     */
    protected function couldBeFilePath(mixed $input): bool
    {
        if (!is_string($input) && !$input instanceof Stringable) {
            return false;
        }

        $input = (string) $input;

        if ($input === '') {
            return false;
        }

        if (strlen($input) > PHP_MAXPATHLEN) {
            return false;
        }

        // file paths usually do not contain control characters (incl. null byte).
        if (preg_match('/[\x00-\x1F\x7F]/', $input) === 1) {
            return false;
        }

        if (str_starts_with($input, DIRECTORY_SEPARATOR)) {
            return true;
        }

        return true;
    }
}
