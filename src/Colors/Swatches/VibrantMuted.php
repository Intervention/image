<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Swatches;

use Intervention\Image\Analyzers\QuantizedPaletteAnalyzer;
use Intervention\Image\Interfaces\AnalyzerInterface;
use Intervention\Image\Interfaces\ColorFilterInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\SwatchesInterface;

class VibrantMuted extends AbstractSwatches implements SwatchesInterface
{
    /**
     * Create new instance.
     */
    public function __construct(
        public ?ColorInterface $vibrant = null,
        public ?ColorInterface $muted = null,
        public ?ColorInterface $darkVibrant = null,
        public ?ColorInterface $darkMuted = null,
        public ?ColorInterface $lightVibrant = null,
        public ?ColorInterface $lightMuted = null,
    ) {
        //
    }

    /**
     * {@inheritdoc}
     *
     * @see SwatchesInterface::colorAnalyzer()
     */
    public function colorAnalyzer(): AnalyzerInterface
    {
        // @phpstan-ignore missingType.checkedException
        return new QuantizedPaletteAnalyzer(256 * 256 * 256);
    }

    /**
     * {@inheritdoc}
     *
     * @see SwatchesInterface::colorFilter()
     */
    public function colorFilter(): ColorFilterInterface
    {
        return new Filters\VibrantMutedFilter();
    }
}
