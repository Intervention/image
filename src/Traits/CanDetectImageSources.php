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

        $decoded = base64_decode($input);

        if (!$decoded) {
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

        // contains non printable ascii
        if (preg_match('/[^ -~]/', $input) === 1) {
            return true;
        }

        // contains only printable ascii
        if (preg_match('/^[ -~]+$/', $input) === 1) {
            return false;
        }

        return true;
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

        if (strlen($input) > PHP_MAXPATHLEN) {
            return false;
        }

        if (str_starts_with($input, DIRECTORY_SEPARATOR)) {
            return true;
        }

        if (preg_match('/[^ -~]/', $input) === 1) {
            return false;
        }

        return true;
    }
}
