<?php

namespace Intervention\Image\Traits;

trait CanValidateBase64
{
    protected function isValidBase64($input): bool
    {
        if (! is_string($input)) {
            return false;
        }

        return base64_encode(base64_decode($input)) === str_replace(["\n", "\r"], '', $input);
    }
}
