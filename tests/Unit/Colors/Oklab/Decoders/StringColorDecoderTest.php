<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Oklab\Decoders;

use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Colors\Oklab\Color;
use Intervention\Image\Colors\Oklab\Decoders\StringColorDecoder;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

#[CoversClass(StringColorDecoder::class)]
final class StringColorDecoderTest extends BaseTestCase
{
    /**
     * @param $channelValues array<float>
     */
    #[DataProvider('decodeDataProvier')]
    public function testDecode(string $input, string $classname, array $channelValues): void
    {
        $decoder = new StringColorDecoder();
        $result = $decoder->decode($input);
        $this->assertInstanceOf($classname, $result);
        $this->assertEquals($channelValues, $result->toArray());
    }

    public static function decodeDataProvier(): Generator
    {
        yield [
            'oklab(0, 0, 0)',
            Color::class,
            [0.0, 0.0, 0.0],
        ];

        yield [
            'oklab(1, 0, 0)',
            Color::class,
            [1.0, 0.0, 0.0],
        ];

        yield [
            'oklab(0.54, 0, 0)',
            Color::class,
            [.54, 0.0, 0.0],
        ];

        yield [
            'oklab(.54, 0, 0)',
            Color::class,
            [.54, 0.0, 0.0],
        ];

        yield [
            'oklab(.548, 0, 0)',
            Color::class,
            [.548, 0.0, 0.0],
        ];

        yield [
            'oklab(.54, 0.3, 0.2)',
            Color::class,
            [.54, .3, .2],
        ];

        yield [
            'oklab(.54, -0.3, -0.2)',
            Color::class,
            [.54, -.3, -.2],
        ];

        yield [
            'oklab(.54, .257, -.199)',
            Color::class,
            [.54, .257, -.199],
        ];

        yield [
            'oklab(25%, .257, -.199)',
            Color::class,
            [.25, .257, -.199],
        ];

        yield [
            'oklab(100%, .257, -.199)',
            Color::class,
            [1.0, .257, -.199],
        ];

        yield [
            'oklab(0%, .257, -.199)',
            Color::class,
            [0.0, .257, -.199],
        ];

        yield [
            'oklab(0%, 25%, -50%)',
            Color::class,
            [0.0, .1, -.2],
        ];

        yield [
            'oklab(.49, -20%, -50%)',
            Color::class,
            [.49, -.08, -.2],
        ];

        yield [
            'oklab (.49, -20%, 50%)',
            Color::class,
            [.49, -.08, .2],
        ];

        yield [
            'oklab (.49,-20%,50%)',
            Color::class,
            [.49, -.08, .2],
        ];

        yield [
            'oklab(.49,-20%,50%)',
            Color::class,
            [.49, -.08, .2],
        ];

        yield [
            'oklab(.49,10%,50%)',
            Color::class,
            [.49, .04, .2],
        ];

        yield [
            'oklab(.49 10% 50%)',
            Color::class,
            [.49, .04, .2],
        ];

        yield [
            'oklab(59.69% 0.1007 0.1191)',
            Color::class,
            [.5969, .1007, .1191],
        ];

        yield [
            'oklab(59.69% 100.00% -50%)',
            Color::class,
            [.5969, .4, -.2],
        ];
    }
}
