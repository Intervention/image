<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface SwatchesInterface extends PaletteInterface
{
    /**
     * Find the best color in the "vibrant" category.
     */
    public function vibrant(): ?ColorInterface;

    /**
     * Find the best color in the "muted" category.
     */
    public function muted(): ?ColorInterface;

    /**
     * Find the best color in the "dark vibrant" category.
     */
    public function darkVibrant(): ?ColorInterface;

    /**
     * Find the best color in the "dark muted" category.
     */
    public function darkMuted(): ?ColorInterface;

    /**
     * Find the best color in the "light vibrant" category.
     */
    public function lightVibrant(): ?ColorInterface;

    /**
     * Find the best color in the "light muted" category.
     */
    public function lightMuted(): ?ColorInterface;
}
