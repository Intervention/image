<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd;

use Intervention\Image\Drivers\AbstractFontProcessor;
use Intervention\Image\Exceptions\FontException;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\FontInterface;
use Intervention\Image\Interfaces\SizeInterface;

class FontProcessor extends AbstractFontProcessor
{
    /**
     * {@inheritdoc}
     *
     * @see FontProcessorInterface::boxSize()
     */
    public function boxSize(string $text, FontInterface $font): SizeInterface
    {
        // if the font has no ttf file the box size is calculated
        // with gd's internal font system: integer values from 1-5
        if (!$font->hasFilename()) {
            // calculate box size from gd font
            $box = new Rectangle(0, 0);
            $chars = mb_strlen($text);
            if ($chars > 0) {
                $box->setWidth(
                    $chars * $this->gdCharacterWidth((int) $font->filename())
                );
                $box->setHeight(
                    $this->gdCharacterHeight((int) $font->filename())
                );
            }
            return $box;
        }

        // build full path to font file to make sure to pass absolute path to imageftbbox()
        // because of issues with different GD version behaving differently when passing
        // relative paths to imageftbbox()
        $fontPath = realpath($font->filename());
        if ($fontPath === false) {
            throw new FontException('Font file ' . $font->filename() . ' does not exist.');
        }

        // calculate box size from ttf font file with angle 0
        $box = imageftbbox(
            size: $this->nativeFontSize($font),
            angle: 0,
            font_filename: $fontPath,
            string: $text,
        );

        if ($box === false) {
            throw new FontException('Unable to calculate box size of font ' . $font->filename() . '.');
        }

        // build size from points
        return new Rectangle(
            width: intval(abs($box[6] - $box[4])), // difference of upper-left-x and upper-right-x
            height: intval(abs($box[7] - $box[1])), // difference if upper-left-y and lower-left-y
            pivot: new Point($box[6], $box[7]), // position of upper-left corner
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see FontProcessorInterface::nativeFontSize()
     */
    public function nativeFontSize(FontInterface $font): float
    {
        return floatval(round($font->size() * .76, 6));
    }

    /**
     * {@inheritdoc}
     *
     * @see FontProcessorInterface::leading()
     */
    public function leading(FontInterface $font): int
    {
        return (int) round(parent::leading($font) * .8);
    }

    /**
     * Return width of a single character
     */
    protected function gdCharacterWidth(int $gdfont): int
    {
        return $gdfont + 4;
    }

    /**
     * Return height of a single character
     */
    protected function gdCharacterHeight(int $gdfont): int
    {
        return match ($gdfont) {
            2, 3 => 14,
            4, 5 => 16,
            default => 8,
        };
    }
}
