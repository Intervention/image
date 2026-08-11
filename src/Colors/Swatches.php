<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use ArrayIterator;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;
use Intervention\Image\Interfaces\SwatchesInterface;
use Intervention\Image\Interfaces\PaletteInterface;
use Traversable;

class Swatches extends Palette implements SwatchesInterface
{
    /**
     * Create new instance.
     *
     * @param array<RatedColor> $colors
     */
    public function __construct(public array $colors)
    {
        //
    }

    /**
     * {@inheritdoc}
     *
     * @see SwatchesInterface::vibrant()
     *
     * @throws ColorException
     */
    public function vibrant(): ColorInterface
    {
        return $this->classifier()->vibrant();
    }

    /**
     * {@inheritdoc}
     *
     * @see SwatchesInterface::muted()
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
     * @see SwatchesInterface::darkVibrant()
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
     * @see SwatchesInterface::darkMuted()
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
     * @see SwatchesInterface::lightVibrant()
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
     * @see SwatchesInterface::lightMuted()
     *
     * @throws ColorException
     */
    public function lightMuted(): ?ColorInterface
    {
        return $this->classifier()->lightMuted();
    }

    /**
     * @throws ColorException
     * @return array<ColorInterface>
     */
    public function swatches(): array
    {
        $colors = [
            $this->vibrant(),
            $this->muted(),
            $this->darkVibrant(),
            $this->darkMuted(),
            $this->lightVibrant(),
            $this->lightMuted(),
        ];

        return array_filter($colors, fn(?ColorInterface $color) => $color !== null);
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::getIterator()
     *
     * @throws ColorException
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->swatches());
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::first()
     *
     * @throws ColorException
     */
    public function first(): ?ColorInterface
    {
        $colors = $this->swatches();

        return reset($colors);
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::last()
     *
     * @throws ColorException
     */
    public function last(): ?ColorInterface
    {
        $colors = $this->swatches();

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
     * @throws ColorException
     */
    public function count(): int
    {
        return count($this->swatches());
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::toColorspace()
     *
     * @throws ColorException
     */
    public function toColorspace(string|ColorspaceInterface $colorspace): SwatchesInterface
    {
        $colors = array_map(
            fn(RatedColor $color): ColorInterface
            => new RatedColor($color->toColorspace($colorspace), $color->rating),
            $this->colors,
        );

        return new self($colors);
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::toArray()
     *
     * @throws ColorException
     */
    public function toArray(): array
    {
        return $this->swatches();
    }

    /**
     * Build color classifier.
     */
    private function classifier(): Classifier
    {
        return new Classifier($this->colors);
    }
}
