<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Rgb;

use Intervention\Image\Colors\Rgb\Channels\Alpha;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Rgb\Colorspace as Rgb;
use Intervention\Image\Colors\Rgb\Decoders\HexColorDecoder;
use Intervention\Image\Exceptions\ColorDecoderException;
use Intervention\Image\InputHandler;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;

enum NamedColor: string implements ColorInterface
{
    case ALICEBLUE = 'aliceblue';
    case ANTIQUEWHITE = 'antiquewhite';
    case AQUA = 'aqua';
    case AQUAMARINE = 'aquamarine';
    case AZURE = 'azure';
    case BEIGE = 'beige';
    case BISQUE = 'bisque';
    case BLACK = 'black';
    case BLANCHEDALMOND = 'blanchedalmond';
    case BLUE = 'blue';
    case BLUEVIOLET = 'blueviolet';
    case BROWN = 'brown';
    case BURLYWOOD = 'burlywood';
    case CADETBLUE = 'cadetblue';
    case CHARTREUSE = 'chartreuse';
    case CHOCOLATE = 'chocolate';
    case CORAL = 'coral';
    case CORNFLOWERBLUE = 'cornflowerblue';
    case CORNSILK = 'cornsilk';
    case CRIMSON = 'crimson';
    case CYAN = 'cyan';
    case DARKBLUE = 'darkblue';
    case DARKCYAN = 'darkcyan';
    case DARKGRAY = 'darkgray';
    case DARKGREEN = 'darkgreen';
    case DARKKHAKI = 'darkkhaki';
    case DARKMAGENTA = 'darkmagenta';
    case DARKOLIVEGREEN = 'darkolivegreen';
    case DARKORANGE = 'darkorange';
    case DARKORCHID = 'darkorchid';
    case DARKRED = 'darkred';
    case DARKSALMON = 'darksalmon';
    case DARKSEAGREEN = 'darkseagreen';
    case DARKSLATEBLUE = 'darkslateblue';
    case DARKSLATEGRAY = 'darkslategray';
    case DARKTURQUOISE = 'darkturquoise';
    case DARKVIOLET = 'darkviolet';
    case DEEPPINK = 'deeppink';
    case DEEPSKYBLUE = 'deepskyblue';
    case DIMGRAY = 'dimgray';
    case DODGERBLUE = 'dodgerblue';
    case FIREBRICK = 'firebrick';
    case FLORALWHITE = 'floralwhite';
    case FORESTGREEN = 'forestgreen';
    case FUCHSIA = 'fuchsia';
    case GAINSBORO = 'gainsboro';
    case GHOSTWHITE = 'ghostwhite';
    case GOLD = 'gold';
    case GOLDENROD = 'goldenrod';
    case GRAY = 'gray';
    case GREEN = 'green';
    case GREENYELLOW = 'greenyellow';
    case HONEYDEW = 'honeydew';
    case HOTPINK = 'hotpink';
    case INDIANRED = 'indianred';
    case INDIGO = 'indigo';
    case IVORY = 'ivory';
    case KHAKI = 'khaki';
    case LAVENDER = 'lavender';
    case LAVENDERBLUSH = 'lavenderblush';
    case LAWNGREEN = 'lawngreen';
    case LEMONCHIFFON = 'lemonchiffon';
    case LIGHTBLUE = 'lightblue';
    case LIGHTCORAL = 'lightcoral';
    case LIGHTCYAN = 'lightcyan';
    case LIGHTGOLDENRODYELLOW = 'lightgoldenrodyellow';
    case LIGHTGRAY = 'lightgray';
    case LIGHTGREEN = 'lightgreen';
    case LIGHTPINK = 'lightpink';
    case LIGHTSALMON = 'lightsalmon';
    case LIGHTSEAGREEN = 'lightseagreen';
    case LIGHTSKYBLUE = 'lightskyblue';
    case LIGHTSLATEGRAY = 'lightslategray';
    case LIGHTSTEELBLUE = 'lightsteelblue';
    case LIGHTYELLOW = 'lightyellow';
    case LIME = 'lime';
    case LIMEGREEN = 'limegreen';
    case LINEN = 'linen';
    case MAGENTA = 'magenta';
    case MAROON = 'maroon';
    case MEDIUMAQUAMARINE = 'mediumaquamarine';
    case MEDIUMBLUE = 'mediumblue';
    case MEDIUMORCHID = 'mediumorchid';
    case MEDIUMPURPLE = 'mediumpurple';
    case MEDIUMSEAGRE = 'mediumseagre';
    case MEDIUMSLATEBLUE = 'mediumslateblue';
    case MEDIUMSPRINGGREEN = 'mediumspringgreen';
    case MEDIUMTURQUOISE = 'mediumturquoise';
    case MEDIUMVIOLETRED = 'mediumvioletred';
    case MIDNIGHTBLUE = 'midnightblue';
    case MINTCREAM = 'mintcream';
    case MISTYROSE = 'mistyrose';
    case MOCCASIN = 'moccasin';
    case NAVAJOWHITE = 'navajowhite';
    case NAVY = 'navy';
    case OLDLACE = 'oldlace';
    case OLIVE = 'olive';
    case OLIVEDRAB = 'olivedrab';
    case ORANGE = 'orange';
    case ORANGERED = 'orangered';
    case ORCHID = 'orchid';
    case PALEGOLDENROD = 'palegoldenrod';
    case PALEGREEN = 'palegreen';
    case PALETURQUOISE = 'paleturquoise';
    case PALEVIOLETRED = 'palevioletred';
    case PAPAYAWHIP = 'papayawhip';
    case PEACHPUFF = 'peachpuff';
    case PERU = 'peru';
    case PINK = 'pink';
    case PLUM = 'plum';
    case POWDERBLUE = 'powderblue';
    case PURPLE = 'purple';
    case RED = 'red';
    case ROSYBROWN = 'rosybrown';
    case ROYALBLUE = 'royalblue';
    case SADDLEBROWN = 'saddlebrown';
    case SALMON = 'salmon';
    case SANDYBROWN = 'sandybrown';
    case SEAGREEN = 'seagreen';
    case SEASHELL = 'seashell';
    case SIENNA = 'sienna';
    case SILVER = 'silver';
    case SKYBLUE = 'skyblue';
    case SLATEBLUE = 'slateblue';
    case SLATEGRAY = 'slategray';
    case SNOW = 'snow';
    case SPRINGGREEN = 'springgreen';
    case STEELBLUE = 'steelblue';
    case TAN = 'tan';
    case TEAL = 'teal';
    case THISTLE = 'thistle';
    case TOMATO = 'tomato';
    case TURQUOISE = 'turquoise';
    case VIOLET = 'violet';
    case WHEAT = 'wheat';
    case WHITE = 'white';
    case WHITESMOKE = 'whitesmoke';
    case YELLOW = 'yellow';
    case YELLOWGREEN = 'yellowgreen';

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::create()
     */
    public static function create(mixed ...$input): ColorInterface
    {
        return self::from(strtolower($input[0]));
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::colorspace()
     */
    public function colorspace(): ColorspaceInterface
    {
        return new Rgb();
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::toString()
     */
    public function toString(): string
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::toHex()
     */
    public function toHex(string $prefix = ''): string
    {
        return $prefix . match ($this) {
            self::ALICEBLUE => 'f0f8ff',
            self::ANTIQUEWHITE => 'faebd7',
            self::AQUA => '00ffff',
            self::AQUAMARINE => '7fffd4',
            self::AZURE => 'f0ffff',
            self::BEIGE => 'f5f5dc',
            self::BISQUE => 'ffe4c4',
            self::BLACK => '000000',
            self::BLANCHEDALMOND => 'ffebcd',
            self::BLUE => '0000ff',
            self::BLUEVIOLET => '8a2be2',
            self::BROWN => 'a52a2a',
            self::BURLYWOOD => 'deb887',
            self::CADETBLUE => '5f9ea0',
            self::CHARTREUSE => '7fff00',
            self::CHOCOLATE => 'd2691e',
            self::CORAL => 'ff7f50',
            self::CORNFLOWERBLUE => '6495ed',
            self::CORNSILK => 'fff8dc',
            self::CRIMSON => 'dc143c',
            self::CYAN => '00ffff',
            self::DARKBLUE => '00008b',
            self::DARKCYAN => '008b8b',
            self::DARKGRAY => 'a9a9a9',
            self::DARKGREEN => '006400',
            self::DARKKHAKI => 'bdb76b',
            self::DARKMAGENTA => '8b008b',
            self::DARKOLIVEGREEN => '556b2f',
            self::DARKORANGE => 'ff8c00',
            self::DARKORCHID => '9932cc',
            self::DARKRED => '8b0000',
            self::DARKSALMON => 'e9967a',
            self::DARKSEAGREEN => '8fbc8f',
            self::DARKSLATEBLUE => '483d8b',
            self::DARKSLATEGRAY => '2f4f4f',
            self::DARKTURQUOISE => '00ced1',
            self::DARKVIOLET => '9400d3',
            self::DEEPPINK => 'ff1493',
            self::DEEPSKYBLUE => '00bfff',
            self::DIMGRAY => '696969',
            self::DODGERBLUE => '1e90ff',
            self::FIREBRICK => 'b22222',
            self::FLORALWHITE => 'fffaf0',
            self::FORESTGREEN => '228b22',
            self::FUCHSIA => 'ff00ff',
            self::GAINSBORO => 'dcdcdc',
            self::GHOSTWHITE => 'f8f8ff',
            self::GOLD => 'ffd700',
            self::GOLDENROD => 'daa520',
            self::GRAY => '808080',
            self::GREEN => '008000',
            self::GREENYELLOW => 'adff2f',
            self::HONEYDEW => 'f0fff0',
            self::HOTPINK => 'ff69b4',
            self::INDIANRED => 'cd5c5c',
            self::INDIGO => '4b0082',
            self::IVORY => 'fffff0',
            self::KHAKI => 'f0e68c',
            self::LAVENDER => 'e6e6fa',
            self::LAVENDERBLUSH => 'fff0f5',
            self::LAWNGREEN => '7cfc00',
            self::LEMONCHIFFON => 'fffacd',
            self::LIGHTBLUE => 'add8e6',
            self::LIGHTCORAL => 'f08080',
            self::LIGHTCYAN => 'e0ffff',
            self::LIGHTGOLDENRODYELLOW => 'fafad2',
            self::LIGHTGRAY => 'd3d3d3',
            self::LIGHTGREEN => '90ee90',
            self::LIGHTPINK => 'ffb6c1',
            self::LIGHTSALMON => 'ffa07a',
            self::LIGHTSEAGREEN => '20b2aa',
            self::LIGHTSKYBLUE => '87cefa',
            self::LIGHTSLATEGRAY => '778899',
            self::LIGHTSTEELBLUE => 'b0c4de',
            self::LIGHTYELLOW => 'ffffe0',
            self::LIME => '00ff00',
            self::LIMEGREEN => '32cd32',
            self::LINEN => 'faf0e6',
            self::MAGENTA => 'ff00ff',
            self::MAROON => '800000',
            self::MEDIUMAQUAMARINE => '66cdaa',
            self::MEDIUMBLUE => '0000cd',
            self::MEDIUMORCHID => 'ba55d3',
            self::MEDIUMPURPLE => '9370db',
            self::MEDIUMSEAGRE => 'en 3cb371',
            self::MEDIUMSLATEBLUE => '7b68ee',
            self::MEDIUMSPRINGGREEN => '00fa9a',
            self::MEDIUMTURQUOISE => '48d1cc',
            self::MEDIUMVIOLETRED => 'c71585',
            self::MIDNIGHTBLUE => '191970',
            self::MINTCREAM => 'f5fffa',
            self::MISTYROSE => 'ffe4e1',
            self::MOCCASIN => 'ffe4b5',
            self::NAVAJOWHITE => 'ffdead',
            self::NAVY => '000080',
            self::OLDLACE => 'fdf5e6',
            self::OLIVE => '808000',
            self::OLIVEDRAB => '6b8e23',
            self::ORANGE => 'ffa500',
            self::ORANGERED => 'ff4500',
            self::ORCHID => 'da70d6',
            self::PALEGOLDENROD => 'eee8aa',
            self::PALEGREEN => '98fb98',
            self::PALETURQUOISE => 'afeeee',
            self::PALEVIOLETRED => 'db7093',
            self::PAPAYAWHIP => 'ffefd5',
            self::PEACHPUFF => 'ffdab9',
            self::PERU => 'cd853f',
            self::PINK => 'ffc0cb',
            self::PLUM => 'dda0dd',
            self::POWDERBLUE => 'b0e0e6',
            self::PURPLE => '800080',
            self::RED => 'ff0000',
            self::ROSYBROWN => 'bc8f8f',
            self::ROYALBLUE => '4169e1',
            self::SADDLEBROWN => '8b4513',
            self::SALMON => 'fa8072',
            self::SANDYBROWN => 'f4a460',
            self::SEAGREEN => '2e8b57',
            self::SEASHELL => 'fff5ee',
            self::SIENNA => 'a0522d',
            self::SILVER => 'c0c0c0',
            self::SKYBLUE => '87ceeb',
            self::SLATEBLUE => '6a5acd',
            self::SLATEGRAY => '708090',
            self::SNOW => 'fffafa',
            self::SPRINGGREEN => '00ff7f',
            self::STEELBLUE => '4682b4',
            self::TAN => 'd2b48c',
            self::TEAL => '008080',
            self::THISTLE => 'd8bfd8',
            self::TOMATO => 'ff6347',
            self::TURQUOISE => '40e0d0',
            self::VIOLET => 'ee82ee',
            self::WHEAT => 'f5deb3',
            self::WHITE => 'ffffff',
            self::WHITESMOKE => 'f5f5f5',
            self::YELLOW => 'ffff00',
            self::YELLOWGREEN => '9acd32',
        };
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::channels()
     */
    public function channels(): array
    {
        return $this->toRgbColor()->channels();
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::channel()
     */
    public function channel(string $classname): ColorChannelInterface
    {
        return $this->toRgbColor()->channel($classname);
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::alpha()
     */
    public function alpha(): ColorChannelInterface
    {
        return new Alpha();
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::toColorspace()
     */
    public function toColorspace(string|ColorspaceInterface $colorspace): ColorInterface
    {
        return $this->toRgbColor()->toColorspace($colorspace);
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::isGrayscale()
     */
    public function isGrayscale(): bool
    {
        return $this->toRgbColor()->isGrayscale();
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::isTransparent()
     */
    public function isTransparent(): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::isClear()
     */
    public function isClear(): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::withTransparency()
     */
    public function withTransparency(float $transparency): ColorInterface
    {
        return $this->toRgbColor()->withTransparency($transparency);
    }

    /**
     * Convert current named color to rgb color object.
     */
    private function toRgbColor(): RgbColor
    {
        return $this->colorspace()->importColor($this);
    }
}
