<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use ArrayIterator;
use Countable;
use Intervention\Image\Analyzers\QuantizedPaletteAnalyzer;
use Intervention\Image\Analyzers\DominantPaletteAnalyzer;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Exceptions\PaletteException;
use Intervention\Image\Interfaces\AnalyzerInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\PaletteInterface;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<ColorInterface>
 */
class Palette implements PaletteInterface, Countable, IteratorAggregate
{
    /**
     * Colors in palette.
     *
     * @var null|array<QuantizedColor>
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
     * @throws PaletteException
     * @return array<ColorInterface>
     */
    public function colors(): array
    {
        return array_values(
            array_map(
                fn(QuantizedColor $paletteColor): ColorInterface => $paletteColor->color,
                $this->extractedColors(),
            ),
        );
    }

    /**
     * Implementation of IteratorAggregate.
     *
     * @throws PaletteException
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
     *
     * @throws PaletteException
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
     *
     * @throws PaletteException
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
     *
     * @throws PaletteException
     */
    public function count(): int
    {
        return count($this->extractedColors());
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::toColorspace()
     *
     * @throws PaletteException
     */
    public function toColorspace(string|ColorspaceInterface $colorspace): PaletteInterface
    {
        $palette = clone $this;

        $palette->colors = array_map(
            fn(QuantizedColor $color): QuantizedColor => new QuantizedColor($color->toColorspace($colorspace)),
            $palette->extractedColors(),
        );

        return $palette;
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::dominant()
     *
     * @throws PaletteException
     */
    public function dominant(int $maxColors = 8): PaletteInterface
    {
        return new self($this->image, new DominantPaletteAnalyzer($maxColors));
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::vibrant()
     *
     * @throws ColorException
     */
    public function vibrant(): ?ColorInterface
    {
        return $this->classifier()->vibrant();
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::muted()
     *
     * @throws ColorException
     */
    public function muted(): ?ColorInterface
    {
        return $this->classifier()->muted();
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::darkVibrant()
     *
     * @throws ColorException
     */
    public function darkVibrant(): ?ColorInterface
    {
        return $this->classifier()->darkVibrant();
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::lightVibrant()
     *
     * @throws ColorException
     */
    public function lightVibrant(): ?ColorInterface
    {
        return $this->classifier()->lightVibrant();
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::darkMuted()
     *
     * @throws ColorException
     */
    public function darkMuted(): ?ColorInterface
    {
        return $this->classifier()->darkMuted();
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::lightMuted()
     *
     * @throws ColorException
     */
    public function lightMuted(): ?ColorInterface
    {
        return $this->classifier()->lightMuted();
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::toArray()
     *
     * @throws PaletteException
     */
    public function toArray(): array
    {
        return $this->colors();
    }

    /**
     * Get color classifier for current palette.
     *
     * @throws PaletteException
     */
    private function classifier(): Classifier
    {
        return new Classifier($this->extractedColors());
    }

    /**
     * Extract colors from image if we've not already done so.
     *
     * @throws PaletteException
     * @return array<QuantizedColor>
     */
    private function extractedColors(): array
    {
        if ($this->colors === null) {
            $colors = $this->image->analyze($this->extractionStrategy);

            if (!is_array($colors)) {
                throw new PaletteException('Failed to extract colors');
            }

            $this->colors = $colors;
        }

        return $this->colors;
    }
}
