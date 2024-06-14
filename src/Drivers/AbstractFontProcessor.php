<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers;

use Intervention\Image\Exceptions\FontException;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\FontInterface;
use Intervention\Image\Interfaces\FontProcessorInterface;
use Intervention\Image\Interfaces\PointInterface;
use Intervention\Image\Typography\Line;
use Intervention\Image\Typography\TextBlock;

abstract class AbstractFontProcessor implements FontProcessorInterface
{
    /**
     * {@inheritdoc}
     *
     * @see FontProcessorInterface::textBlock()
     */
    public function textBlock(string $text, FontInterface $font, PointInterface $position): TextBlock
    {
        $lines = $this->wrapTextBlock(new TextBlock($text), $font);
        $pivot = $this->buildPivot($lines, $font, $position);

        $leading = $this->leading($font);
        $blockWidth = $this->boxSize((string) $lines->longestLine(), $font)->width();

        $x = $pivot->x();
        $y = $font->hasFilename() ? $pivot->y() + $this->capHeight($font) : $pivot->y();
        $xAdjustment = 0;

        // adjust line positions according to alignment
        foreach ($lines as $line) {
            $lineBoxSize = $this->boxSize((string) $line, $font);
            $lineWidth = $lineBoxSize->width() + $lineBoxSize->pivot()->x();
            $xAdjustment = $font->alignment() == 'left' ? 0 : $blockWidth - $lineWidth;
            $xAdjustment = $font->alignment() == 'right' ? intval(round($xAdjustment)) : $xAdjustment;
            $xAdjustment = $font->alignment() == 'center' ? intval(round($xAdjustment / 2)) : $xAdjustment;
            $position = new Point($x + $xAdjustment, $y);
            $position->rotate($font->angle(), $pivot);
            $line->setPosition($position);
            $y += $leading;
        }

        return $lines;
    }

    /**
     * {@inheritdoc}
     *
     * @see FontProcessorInterface::nativeFontSize()
     */
    public function nativeFontSize(FontInterface $font): float
    {
        return $font->size();
    }

    /**
     * {@inheritdoc}
     *
     * @see FontProcessorInterface::typographicalSize()
     */
    public function typographicalSize(FontInterface $font): int
    {
        return $this->boxSize('Hy', $font)->height();
    }

    /**
     * {@inheritdoc}
     *
     * @see FontProcessorInterface::capHeight()
     */
    public function capHeight(FontInterface $font): int
    {
        return $this->boxSize('T', $font)->height();
    }

    /**
     * {@inheritdoc}
     *
     * @see FontProcessorInterface::leading()
     */
    public function leading(FontInterface $font): int
    {
        return intval(round($this->typographicalSize($font) * $font->lineHeight()));
    }

    /**
     * Reformat a text block by wrapping each line before the given maximum width
     *
     * @param TextBlock $block
     * @param FontInterface $font
     * @throws FontException
     * @return TextBlock
     */
    protected function wrapTextBlock(TextBlock $block, FontInterface $font): TextBlock
    {
        $newLines = [];
        foreach ($block as $line) {
            foreach ($this->wrapLine($line, $font) as $newLine) {
                $newLines[] = $newLine;
            }
        }

        return $block->setLines($newLines);
    }

    /**
     * Check if a line exceeds the given maximum width and wrap it if necessary.
     * The output will be an array of formatted lines that are all within the
     * maximum width.
     *
     * @param Line $line
     * @param FontInterface $font
     * @throws FontException
     * @return array<Line>
     */
    protected function wrapLine(Line $line, FontInterface $font): array
    {
        // no wrap width - no wrapping
        if (is_null($font->wrapWidth())) {
            return [$line];
        }

        $wrapped = [];
        $formattedLine = new Line();

        foreach ($line as $word) {
            // calculate width of newly formatted line
            $lineWidth = $this->boxSize(match ($formattedLine->count()) {
                0 => $word,
                default => (string) $formattedLine . ' ' . $word,
            }, $font)->width();

            // decide if word fits on current line or a new line must be created
            if ($line->count() === 1 || $lineWidth <= $font->wrapWidth()) {
                $formattedLine->add($word);
            } else {
                if ($formattedLine->count()) {
                    $wrapped[] = $formattedLine;
                }
                $formattedLine = new Line($word);
            }
        }

        $wrapped[] = $formattedLine;

        return $wrapped;
    }

    /**
     * Build pivot point of textblock according to the font settings and based on given position
     *
     * @param TextBlock $block
     * @param FontInterface $font
     * @param PointInterface $position
     * @throws FontException
     * @return PointInterface
     */
    protected function buildPivot(TextBlock $block, FontInterface $font, PointInterface $position): PointInterface
    {
        // bounding box
        $box = (new Rectangle(
            $this->boxSize((string) $block->longestLine(), $font)->width(),
            $this->leading($font) * ($block->count() - 1) + $this->capHeight($font)
        ));

        // set position
        $box->setPivot($position);

        // alignment
        $box->align($font->alignment());
        $box->valign($font->valignment());
        $box->rotate($font->angle());

        return $box->last();
    }
}
