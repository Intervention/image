<?php

namespace Intervention\Image\Interfaces;

interface FontInterface
{
    /**
     * Set color of font
     *
     * @param mixed $color
     * @return FontInterface
     */
    public function setColor(mixed $color): self;

    /**
     * Get color of font
     *
     * @return mixed
     */
    public function color(): mixed;

    /**
     * Set font size
     *
     * @param float $size
     * @return FontInterface
     */
    public function setSize(float $size): self;

    /**
     * Get font size
     *
     * @return float
     */
    public function size(): float;

    /**
     * Set rotation angle of font
     *
     * @param float $angle
     * @return FontInterface
     */
    public function setAngle(float $angle): self;

    /**
     * Get rotation angle of font
     *
     * @return float
     */
    public function angle(): float;

    /**
     * Set font filename
     *
     * @param string $filename
     * @return FontInterface
     */
    public function setFilename(string $filename): self;

    /**
     * Get font filename
     *
     * @return null|string
     */
    public function filename(): ?string;

    /**
     * Determine if font has a corresponding filename
     *
     * @return bool
     */
    public function hasFilename(): bool;

    /**
     * Set horizontal alignment of font
     *
     * @param string $align
     * @return FontInterface
     */
    public function setAlignment(string $align): self;

    /**
     * Get horizontal alignment of font
     *
     * @return string
     */
    public function alignment(): string;

    /**
     * Set vertical alignment of font
     *
     * @param string $align
     * @return FontInterface
     */
    public function setValignment(string $align): self;

    /**
     * Get vertical alignment of font
     *
     * @return string
     */
    public function valignment(): string;

    /**
     * Set typographical line height
     *
     * @param float $value
     * @return FontInterface
     */
    public function setLineHeight(float $value): self;

    /**
     * Get line height of font
     *
     * @return float
     */
    public function lineHeight(): float;
}
