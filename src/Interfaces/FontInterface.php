<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Alignment;

interface FontInterface
{
    /**
     * Set color of font
     */
    public function setColor(mixed $color): self;

    /**
     * Get color of font
     */
    public function color(): mixed;

    /**
     * Set stroke color of font
     */
    public function setStrokeColor(mixed $color): self;

    /**
     * Get stroke color of font
     */
    public function strokeColor(): mixed;

    /**
        /**
    * Set stroke width of font
    */
    public function setStrokeWidth(int $width): self;

    /**
     * Get stroke width of font
     */
    public function strokeWidth(): int;

    /**
     * Determine if the font is drawn with outline stroke effect
     */
    public function hasStrokeEffect(): bool;

    /**
     * Set font size
     */
    public function setSize(float $size): self;

    /**
     * Get font size
     */
    public function size(): float;

    /**
     * Set rotation angle of font
     */
    public function setAngle(float $angle): self;

    /**
     * Get rotation angle of font
     */
    public function angle(): float;

    /**
     * Set font file path
     */
    public function setFilepath(string $path): self;

    /**
     * Get font file path
     */
    public function filepath(): ?string;

    /**
     * Determine if font has a corresponding file
     */
    public function hasFile(): bool;

    /**
     * Set horizontal alignment of font
     */
    public function setAlignment(string|Alignment $align): self;

    /**
     * Get horizontal alignment of font
     */
    public function alignment(): Alignment;

    /**
     * Set vertical alignment of font
     */
    public function setValignment(string|Alignment $align): self;

    /**
     * Get vertical alignment of font
     */
    public function valignment(): Alignment;

    /**
     * Set typographical line height
     */
    public function setLineHeight(float $value): self;

    /**
     * Get line height of font
     */
    public function lineHeight(): float;

    /**
     *  Set the wrap width with which the text is rendered
     */
    public function setWrapWidth(?int $width): self;

    /**
     * Get wrap width with which the text is rendered
     */
    public function wrapWidth(): ?int;
}
