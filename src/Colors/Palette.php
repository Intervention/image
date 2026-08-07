<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use ArrayIterator;
use Countable;
use Intervention\Image\Analyzers\ColorPaletteAnalyzer;
use Intervention\Image\Interfaces\AnalyzerInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\PaletteInterface;
use IteratorAggregate;
use Traversable;

class Palette implements PaletteInterface, Countable, IteratorAggregate
{
    /**
     * @var null|array<mixed>
     */
    protected ?array $colors = null;

    /**
     * Create new instance.
     */
    public function __construct(
        protected ImageInterface $image,
        protected AnalyzerInterface $extractionStrategy = new ColorPaletteAnalyzer(),
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
        return array_map(
            fn(array $normalized) => $this->colorFromNormalized($normalized),
            $this->extractedColors(),
        );
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

        return $this->colorFromNormalized(reset($colors));
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
    public function dominant(int $size = 10): PaletteInterface
    {
        return new self($this->image);
    }

    /**
     * @param array<float> $normalized
     */
    private function colorFromNormalized(array $normalized): ColorInterface
    {
        return $this->image->colorspace()::colorFromNormalized($normalized);
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
