<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use ArrayIterator;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;
use Intervention\Image\Interfaces\PaletteInterface;
use Traversable;

class Swatches extends Palette
{
    /**
     * Create new instance.
     */
    public function __construct(
        public ?ColorInterface $vibrant,
        public ?ColorInterface $muted,
        public ?ColorInterface $darkVibrant,
        public ?ColorInterface $darkMuted,
        public ?ColorInterface $lightVibrant,
        public ?ColorInterface $lightMuted,
    ) {
        //
    }

    /**
     * Find the best color in the "vibrant" category.
     *
     * @throws ColorException
     */
    public function vibrant(): ?ColorInterface
    {
        return $this->vibrant;
    }

    /**
     * Find the best color in the "muted" category.
     *
     * @throws ColorException
     */
    public function muted(): ?ColorInterface
    {
        return $this->muted;
    }

    /**
     * Find the best color in the "dark vibrant" category.
     *
     * @throws ColorException
     */
    public function darkVibrant(): ?ColorInterface
    {
        return $this->darkVibrant;
    }

    /**
     * Find the best color in the "dark muted" category.
     *
     * @throws ColorException
     */
    public function darkMuted(): ?ColorInterface
    {
        return $this->darkMuted;
    }

    /**
     * Find the best color in the "light vibrant" category.
     *
     * @throws ColorException
     */
    public function lightVibrant(): ?ColorInterface
    {
        return $this->lightVibrant;
    }

    /**
     * Find the best color in the "light muted" category.
     *
     * @throws ColorException
     */
    public function lightMuted(): ?ColorInterface
    {
        return $this->lightMuted;
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
    public function toColorspace(string|ColorspaceInterface $colorspace): self
    {
        $colors = array_map(
            fn(RatedColor $color): ColorInterface
            => new RatedColor($color->toColorspace($colorspace), $color->rating),
            $this->swatches(),
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
}
