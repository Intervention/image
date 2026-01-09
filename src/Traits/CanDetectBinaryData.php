<?php

declare(strict_types=1);

namespace Intervention\Image\Traits;

use Stringable;

trait CanDetectBinaryData
{
    public function isBinary(mixed $data): bool
    {
        if (!is_string($data) && !$data instanceof Stringable) {
            return false;
        }

        $data = (string) $data;

        // contains non printable ascii
        if (preg_match('/[^ -~]/', $data) === 1) {
            return true;
        }

        // contains only printable ascii
        if (preg_match('/^[ -~]+$/', $data) === 1) {
            return false;
        }

        return true;
    }
}
