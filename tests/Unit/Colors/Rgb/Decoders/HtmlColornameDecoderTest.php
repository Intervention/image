<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Rgb\Decoders;

use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Colors\Rgb\Decoders\HtmlColornameDecoder;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

#[RequiresPhpExtension('gd')]
#[CoversClass(\Intervention\Image\Colors\Rgb\Decoders\HtmlColorNameDecoder::class)]
final class HtmlColornameDecoderTest extends BaseTestCase
{
    #[DataProvider('decodeDataProvier')]
    public function testDecode(string $input, string $classname, array $channelValues): void
    {
        $decoder = new HtmlColornameDecoder();
        $result = $decoder->decode($input);
        $this->assertInstanceOf($classname, $result);
        $this->assertEquals($channelValues, $result->toArray());
    }

    public static function decodeDataProvier(): Generator
    {
        yield [
            'salmon',
            Color::class,
            [250, 128, 114, 255],
        ];
        yield [
            'khaki',
            Color::class,
            [240, 230, 140, 255],
        ];
        yield [
            'peachpuff',
            Color::class,
            [255, 218, 185, 255],
        ];
    }
}
