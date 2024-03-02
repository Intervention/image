<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\FontException;

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
     * Set stroke color of font
     *
     * @param mixed $color
     * @return FontInterface
     */
    public function setStrokeColor(mixed $color): self;

    /**
     * Get stroke color of font
     *
     * @return mixed
     */
    public function strokeColor(): mixed;

    /**
    /**
     * Set stroke width of font
     *
     * @param int $width
     * @throws FontException
     * @return FontInterface
     */
    public function setStrokeWidth(int $width): self;

    /**
     * Get stroke width of font
     *
     * @return int
     */
    public function strokeWidth(): int;

    /**
     * Determine if the font is drawn with outline stroke effect
     *
     * @return bool
     */
    public function hasStrokeEffect(): bool;

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

    /**
     *  Set the wrap width with which the text is rendered
     *
     * @param int $width
     * @return FontInterface
     */
    public function setWrapWidth(?int $width): self;

    /**
     * Get wrap width with which the text is rendered
     *
     * @return null|int
     */
    public function wrapWidth(): ?int;
}
