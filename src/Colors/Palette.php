<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use ArrayIterator;
use Countable;
use Intervention\Image\Analyzers\QuantizedPaletteAnalyzer;
use Intervention\Image\Analyzers\KmeansPaletteAnalyzer;
use Intervention\Image\Interfaces\AnalyzerInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\PaletteInterface;
use IteratorAggregate;
use Traversable;

class Palette implements PaletteInterface, Countable, IteratorAggregate
{
    /**
     * @var null|array<ColorInterface>
     */
    protected ?array $colors = null;

    /**
     * Create new instance.
     */
    public function __construct(
        protected ImageInterface $image,
        protected AnalyzerInterface $extractionStrategy = new QuantizedPaletteAnalyzer(),
    ) {
        //
    }

    /**
     * Get colors of palette.
     *
     * @return array<ColorInterface>
     */
    public function colors(): array
    {
        return $this->extractedColors();
    }

    /**
     * Implementation of IteratorAggregate.
     *
     * @return Traversable<ColorInterface>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->colors());
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::first()
     */
    public function first(): ColorInterface
    {
        $colors = $this->extractedColors();

        return reset($colors);
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::count()
     */
    public function count(): int
    {
        return count($this->extractedColors());
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::dominant()
     */
    public function dominant(int $maxColors = 16): PaletteInterface
    {
        return new self($this->image, new KmeansPaletteAnalyzer($maxColors));
    }

    /**
     * Extract colors from image if we've not already done so.
     *
     * @return array<mixed>
     */
    public function extractedColors(): array
    {
        if ($this->colors === null) {
            $this->colors = $this->image->analyze($this->extractionStrategy);
        }

        return $this->colors;
    }
}
