<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\FontException;

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
    *
    * @throws FontException
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
     * Set font filename
     */
    public function setFilename(string $filename): self;

    /**
     * Get font filename
     */
    public function filename(): ?string;

    /**
     * Determine if font has a corresponding filename
     */
    public function hasFilename(): bool;

    /**
     * Set horizontal alignment of font
     */
    public function setAlignment(string $align): self;

    /**
     * Get horizontal alignment of font
     */
    public function alignment(): string;

    /**
     * Set vertical alignment of font
     */
    public function setValignment(string $align): self;

    /**
     * Get vertical alignment of font
     */
    public function valignment(): string;

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
