<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

/**
 * Trims the image by given tolerance level
 *
 * @property int $tolerance
 */
class TrimModifier extends SpecializableModifier
{
    /**
     * Class constructor
     *
     * @param int $tolerance Tolerance level for trim operation
     */
    public function __construct(public int $tolerance = 0) {}
}
