<?php
namespace Intervention\Image\Modifiers;

/**
 * Trims the image by given tolerance level
 */
class TrimModifier extends SpecializableModifier
{
    /**
     * Class constructor
     *
     * @param  int $tolerance Tolerance level for trim operation
     */
    public function __construct(
        /**
         * Tolerance level for trim operation
         * 
         * @var int
         */
        public int $tolerance = 0,
    ) {}
}