<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Rgb\Decoders;

use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class HtmlColornameDecoder extends HexColorDecoder implements DecoderInterface
{
    /**
     * Available color names and their corresponding hex codes
     *
     * @var array<string, string>
     */
    protected static array $names = [
        'lightsalmon' => '#ffa07a',
        'salmon' => '#fa8072',
        'darksalmon' => '#e9967a',
        'lightcoral' => '#f08080',
        'indianred' => '#cd5c5c',
        'crimson' => '#dc143c',
        'firebrick' => '#b22222',
        'red' => '#ff0000',
        'darkred' => '#8b0000',
        'coral' => '#ff7f50',
        'tomato' => '#ff6347',
        'orangered' => '#ff4500',
        'gold' => '#ffd700',
        'orange' => '#ffa500',
        'darkorange' => '#ff8c00',
        'lightyellow' => '#ffffe0',
        'lemonchiffon' => '#fffacd',
        'lightgoldenrodyellow' => '#fafad2',
        'papayawhip' => '#ffefd5',
        'moccasin' => '#ffe4b5',
        'peachpuff' => '#ffdab9',
        'palegoldenrod' => '#eee8aa',
        'khaki' => '#f0e68c',
        'darkkhaki' => '#bdb76b',
        'yellow' => '#ffff00',
        'lawngreen' => '#7cfc00',
        'chartreuse' => '#7fff00',
        'limegreen' => '#32cd32',
        'lime' => '#00ff00',
        'forestgreen' => '#228b22',
        'green' => '#008000',
        'darkgreen' => '#006400',
        'greenyellow' => '#adff2f',
        'yellowgreen' => '#9acd32',
        'springgreen' => '#00ff7f',
        'mediumspringgreen' => '#00fa9a',
        'lightgreen' => '#90ee90',
        'palegreen' => '#98fb98',
        'darkseagreen' => '#8fbc8f',
        'mediumseagre' => 'en #3cb371',
        'seagreen' => '#2e8b57',
        'olive' => '#808000',
        'darkolivegreen' => '#556b2f',
        'olivedrab' => '#6b8e23',
        'lightcyan' => '#e0ffff',
        'cyan' => '#00ffff',
        'aqua' => '#00ffff',
        'aquamarine' => '#7fffd4',
        'mediumaquamarine' => '#66cdaa',
        'paleturquoise' => '#afeeee',
        'turquoise' => '#40e0d0',
        'mediumturquoise' => '#48d1cc',
        'darkturquoise' => '#00ced1',
        'lightseagreen' => '#20b2aa',
        'cadetblue' => '#5f9ea0',
        'darkcyan' => '#008b8b',
        'teal' => '#008080',
        'powderblue' => '#b0e0e6',
        'lightblue' => '#add8e6',
        'lightskyblue' => '#87cefa',
        'skyblue' => '#87ceeb',
        'deepskyblue' => '#00bfff',
        'lightsteelblue' => '#b0c4de',
        'dodgerblue' => '#1e90ff',
        'cornflowerblue' => '#6495ed',
        'steelblue' => '#4682b4',
        'royalblue' => '#4169e1',
        'blue' => '#0000ff',
        'mediumblue' => '#0000cd',
        'darkblue' => '#00008b',
        'navy' => '#000080',
        'midnightblue' => '#191970',
        'mediumslateblue' => '#7b68ee',
        'slateblue' => '#6a5acd',
        'darkslateblue' => '#483d8b',
        'lavender' => '#e6e6fa',
        'thistle' => '#d8bfd8',
        'plum' => '#dda0dd',
        'violet' => '#ee82ee',
        'orchid' => '#da70d6',
        'fuchsia' => '#ff00ff',
        'magenta' => '#ff00ff',
        'mediumorchid' => '#ba55d3',
        'mediumpurple' => '#9370db',
        'blueviolet' => '#8a2be2',
        'darkviolet' => '#9400d3',
        'darkorchid' => '#9932cc',
        'darkmagenta' => '#8b008b',
        'purple' => '#800080',
        'indigo' => '#4b0082',
        'pink' => '#ffc0cb',
        'lightpink' => '#ffb6c1',
        'hotpink' => '#ff69b4',
        'deeppink' => '#ff1493',
        'palevioletred' => '#db7093',
        'mediumvioletred' => '#c71585',
        'white' => '#ffffff',
        'snow' => '#fffafa',
        'honeydew' => '#f0fff0',
        'mintcream' => '#f5fffa',
        'azure' => '#f0ffff',
        'aliceblue' => '#f0f8ff',
        'ghostwhite' => '#f8f8ff',
        'whitesmoke' => '#f5f5f5',
        'seashell' => '#fff5ee',
        'beige' => '#f5f5dc',
        'oldlace' => '#fdf5e6',
        'floralwhite' => '#fffaf0',
        'ivory' => '#fffff0',
        'antiquewhite' => '#faebd7',
        'linen' => '#faf0e6',
        'lavenderblush' => '#fff0f5',
        'mistyrose' => '#ffe4e1',
        'gainsboro' => '#dcdcdc',
        'lightgray' => '#d3d3d3',
        'silver' => '#c0c0c0',
        'darkgray' => '#a9a9a9',
        'gray' => '#808080',
        'dimgray' => '#696969',
        'lightslategray' => '#778899',
        'slategray' => '#708090',
        'darkslategray' => '#2f4f4f',
        'black' => '#000000',
        'cornsilk' => '#fff8dc',
        'blanchedalmond' => '#ffebcd',
        'bisque' => '#ffe4c4',
        'navajowhite' => '#ffdead',
        'wheat' => '#f5deb3',
        'burlywood' => '#deb887',
        'tan' => '#d2b48c',
        'rosybrown' => '#bc8f8f',
        'sandybrown' => '#f4a460',
        'goldenrod' => '#daa520',
        'peru' => '#cd853f',
        'chocolate' => '#d2691e',
        'saddlebrown' => '#8b4513',
        'sienna' => '#a0522d',
        'brown' => '#a52a2a',
        'maroon' => '#800000',
    ];

    /**
     * Decode html color names
     *
     * @param mixed $input
     * @return ImageInterface|ColorInterface
     */
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!is_string($input)) {
            throw new DecoderException('Unable to decode input');
        }

        if (!array_key_exists(strtolower($input), static::$names)) {
            throw new DecoderException('Unable to decode input');
        }

        return parent::decode(static::$names[strtolower($input)]);
    }
}
