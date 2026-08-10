<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use ArrayIterator;
use Countable;
use Intervention\Image\Analyzers\QuantizedPaletteAnalyzer;
use Intervention\Image\Analyzers\DominantPaletteAnalyzer;
use Intervention\Image\Interfaces\AnalyzerInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\PaletteInterface;
use IteratorAggregate;
use Traversable;

class Palette implements PaletteInterface, Countable, IteratorAggregate
{
    /**
     * Colors in palette.
     *
     * @var null|array<PaletteColor>
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
        return array_values(
            array_map(
                fn(PaletteColor $paletteColor): ColorInterface => $paletteColor->color,
                $this->extractedColors(),
            ),
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
    public function first(): ?ColorInterface
    {
        $colors = $this->colors();

        return reset($colors);
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::last()
     */
    public function last(): ?ColorInterface
    {
        $colors = $this->colors();

        if (count($colors) === 0) {
            return null;
        }

        return end($colors);
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

    public function toColorspace(string|ColorspaceInterface $colorspace): PaletteInterface
    {
        $palette = clone $this;

        $palette->colors = array_map(
            fn(PaletteColor $color): PaletteColor => new PaletteColor($color->toColorspace($colorspace)),
            $palette->extractedColors(),
        );

        return $palette;
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::dominant()
     */
    public function dominant(int $maxColors = 16): PaletteInterface
    {
        return new self($this->image, new DominantPaletteAnalyzer($maxColors));
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::vibrant()
     */
    public function vibrant(): ?ColorInterface
    {
        return $this->classifier()->vibrant();
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::muted()
     */
    public function muted(): ?ColorInterface
    {
        return $this->classifier()->muted();
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::darkVibrant()
     */
    public function darkVibrant(): ?ColorInterface
    {
        return $this->classifier()->darkVibrant();
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::lightVibrant()
     */
    public function lightVibrant(): ?ColorInterface
    {
        return $this->classifier()->lightVibrant();
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::darkMuted()
     */
    public function darkMuted(): ?ColorInterface
    {
        return $this->classifier()->darkMuted();
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::lightMuted()
     */
    public function lightMuted(): ?ColorInterface
    {
        return $this->classifier()->lightMuted();
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::toArray()
     */
    public function toArray(): array
    {
        return $this->colors();
    }

    /**
     * Get color classifier for current palette.
     */
    private function classifier(): Classifier
    {
        return new Classifier($this->extractedColors());
    }

    /**
     * Extract colors from image if we've not already done so.
     *
     * @return array<PaletteColor>
     */
    private function extractedColors(): array
    {
        if ($this->colors === null) {
            $this->colors = $this->image->analyze($this->extractionStrategy);
        }

        return $this->colors;
    }
}
