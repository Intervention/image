<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers;

use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\FontInterface;
use Intervention\Image\Interfaces\FontProcessorInterface;
use Intervention\Image\Interfaces\PointInterface;
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
        $lines = new TextBlock($text);
        $pivot = $this->buildPivot($lines, $font, $position);

        $leading = $this->leading($font);
        $blockWidth = $this->boxSize((string) $lines->longestLine(), $font)->width();

        $x = $pivot->x();
        $y = $font->hasFilename() ? $pivot->y() + $this->capHeight($font) : $pivot->y();
        $x_adjustment = 0;

        foreach ($lines as $line) {
            $line_width = $this->boxSize((string) $line, $font)->width();
            $x_adjustment = $font->alignment() == 'left' ? 0 : $blockWidth - $line_width;
            $x_adjustment = $font->alignment() == 'right' ? intval(round($x_adjustment)) : $x_adjustment;
            $x_adjustment = $font->alignment() == 'center' ? intval(round($x_adjustment / 2)) : $x_adjustment;
            $position = new Point($x + $x_adjustment, $y);
            $position->rotate($font->angle(), $pivot);
            $line->setPosition($position);
            $y += $leading;
        }

        return $lines;
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
     * Build pivot point of textblock according to the font settings and based on given position
     *
     * @param TextBlock $block
     * @param FontInterface $font
     * @param PointInterface $position
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
