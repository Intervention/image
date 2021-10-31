<?php

namespace Intervention\Image\Traits;

trait CanValidateColors
{
    protected function isValidColorArray($input): bool
    {
        if (!is_array($input)) {
            return false;
        }

        if (count($input) < 3 || count($input) > 4) {
            return false;
        }

        // validate rgb values
        foreach ($input as $value) {
            if ($value < 0 || $value > 255) {
                return false;
            }
        }

        // validate alpha value
        if (isset($input[3]) && ($input[3] > 1 || $input[3] < 0)) {
            return false;
        }

        return true;
    }
}
