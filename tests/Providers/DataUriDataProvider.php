<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Providers;

use Generator;
use Intervention\Image\Exceptions\InvalidArgumentException;

class DataUriDataProvider
{
    public static function validDataUris(): Generator
    {
        yield [
            'data:,', // input
            '', // data
        ];
        yield [
            'data:,foo',
            'foo',
        ];
        yield [
            'data:;base64,Zm9v',
            'foo',
        ];
        yield [
            'data:,foo%20bar',
            'foo bar',
        ];
        yield [
            'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAFCAYAAACNbyblAAAAH' .
                'ElEQVQI12P4//8/w38GIAXDIBKE0DHxgljNBAAO9TXL0Y4OHwAAAABJRU5ErkJggg==',
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAUAAAAFCAYAAACNbyblAAAAH' .
                'ElEQVQI12P4//8/w38GIAXDIBKE0DHxgljNBAAO9TXL0Y4OHwAAAABJRU5ErkJggg=='),
        ];
        yield [
            'data:text/vnd-example+xyz;foo=bar;base64,R0lGODdh',
            'GIF87a',
        ];
        yield [
            'data:text/vnd-example+xyz;foo=bar;bar-baz=false;base64,R0lGODdh',
            'GIF87a',
        ];
        yield [
            'data:text/plain;charset=UTF-8;page=21,the%20data:1234,5678',
            'the data:1234,5678',
        ];
        yield [
            'data:text/plain;charset=US-ASCII,foobar',
            'foobar',
        ];
        yield [
            'data:text/plain,foobar',
            'foobar',
        ];
        yield [
            'data:,VGhlIHF1aWNrIGJyb3duIGZveCBqdW1wcyBvdmVyIHRoZSBsYXp5IGRvZy=',
            'VGhlIHF1aWNrIGJyb3duIGZveCBqdW1wcyBvdmVyIHRoZSBsYXp5IGRvZy=',
        ];
        yield [
            'data:,Hello%2C%20World%21',
            'Hello, World!',
        ];
        yield [
            'data:text/plain;base64,SGVsbG8sIFdvcmxkIQ==',
            'Hello, World!',
        ];
        yield [
            'data:text/html,<script>alert(\'hi\');</script>',
            '<script>alert(\'hi\');</script>',
        ];
    }

    public static function invalidDataUris(): Generator
    {
        yield [
            'foo',
            InvalidArgumentException::class,
        ];
        yield [
            'bar',
            InvalidArgumentException::class,
        ];
        yield [
            'data:',
            InvalidArgumentException::class,
        ];
        yield [
            'VGhlIHF1aWNrIGJyb3duIGZveCBqdW1wcyBvdmVyIHRoZSBsYXp5IGRvZy4=',
            InvalidArgumentException::class,
        ];
        yield [
            'data;xt;4,SGVsbG8sIFdvcmxkIQ==',
            InvalidArgumentException::class,
        ];
    }
}
