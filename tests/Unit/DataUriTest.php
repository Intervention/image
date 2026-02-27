<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use Generator;
use Intervention\Image\DataUri;
use Intervention\Image\MediaType;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Providers\DataUriDataProvider;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;

class DataUriTest extends BaseTestCase
{
    public function testSetGetData(): void
    {
        $datauri = new DataUri(data: 'test');
        $this->assertEquals('test', $datauri->data());
        $datauri->setData('foo');
        $this->assertEquals('foo', $datauri->data());
    }

    #[DataProvider('getSetMediaTypeDataProvider')]
    public function testGetSetMediaType(mixed $inputMediaType, ?string $resultMediaType): void
    {
        $datauri = new DataUri(mediaType: $inputMediaType);
        $this->assertEquals($resultMediaType, $datauri->mediaType());
        $datauri->setMediaType(null);
        $this->assertNull($datauri->mediaType());
    }

    public static function getSetMediaTypeDataProvider(): Generator
    {
        yield [null, null];
        yield ['', null];
        yield ['image/jpeg', 'image/jpeg'];
        yield ['image/gif', 'image/gif'];
        yield [MediaType::IMAGE_AVIF, 'image/avif'];
    }

    public function testSetGetParameters(): void
    {
        $datauri = new DataUri();
        $this->assertEquals([], $datauri->parameters());
        $datauri->setParameters(['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], $datauri->parameters());
        $datauri->setParameters(['bar' => 'baz', 'test' => 123]);
        $this->assertEquals(['bar' => 'baz', 'test' => '123'], $datauri->parameters());
        $datauri->setParameter('test', '456');
        $this->assertEquals(['bar' => 'baz', 'test' => '456'], $datauri->parameters());
        $datauri->appendParameters(['bar' => 'foobar', 'append' => 'ok']);
        $this->assertEquals(['bar' => 'foobar', 'test' => '456', 'append' => 'ok'], $datauri->parameters());
        $this->assertEquals('foobar', $datauri->parameter('bar'));
        $this->assertEquals('456', $datauri->parameter('test'));
        $this->assertEquals('ok', $datauri->parameter('append'));
        $this->assertEquals(null, $datauri->parameter('none'));
        $datauri->setCharset('utf-8');
        $this->assertEquals('utf-8', $datauri->charset());
        $this->assertEquals([
            'bar' => 'foobar',
            'test' => '456',
            'append' => 'ok',
            'charset' => 'utf-8',
        ], $datauri->parameters());
    }

    /**
     * @param array<string, string> $parameters
     */
    #[DataProvider('toStringDataProvider')]
    public function testToString(
        string $data,
        null|string|MediaType $mediaType,
        array $parameters,
        bool $isBase64Encoded,
        string $result,
    ): void {
        $datauri = new DataUri($data, $mediaType, $parameters, $isBase64Encoded);
        $this->assertEquals($result, $datauri->toString());
        $this->assertEquals($result, (string) $datauri);
    }

    public static function toStringDataProvider(): Generator
    {
        yield [
            '',
            null,
            [],
            false,
            'data:,'
        ];

        yield [
            'foo',
            null,
            [],
            false,
            'data:,foo'
        ];

        yield [
            'foo',
            'text/plain',
            [],
            false,
            'data:text/plain,foo'
        ];

        yield [
            'foo',
            'text/plain',
            ['charset' => 'utf-8'],
            false,
            'data:text/plain;charset=utf-8,foo'
        ];

        yield [
            'foo',
            'text/plain',
            ['charset' => 'utf-8'],
            true,
            'data:text/plain;charset=utf-8;base64,foo'
        ];
    }

    #[DataProviderExternal(DataUriDataProvider::class, 'validDataUris')]
    public function testDecode(string $dataUriScheme, string $resultData): void
    {
        $datauri = DataUri::decode($dataUriScheme);
        $this->assertInstanceOf(DataUri::class, $datauri);
        $this->assertEquals($resultData, $datauri->data());
    }

    #[DataProviderExternal(DataUriDataProvider::class, 'invalidDataUris')]
    public function testDecodeInvalid(string $input, string $exception): void
    {
        $this->expectException($exception);
        DataUri::decode($input);
    }

    public function testCreateStaticFactory(): void
    {
        $datauri = DataUri::create('test-data', 'text/plain', ['charset' => 'utf-8']);
        $this->assertInstanceOf(DataUri::class, $datauri);
        $this->assertEquals('test-data', $datauri->data());
        $this->assertEquals('text/plain', $datauri->mediaType());
        $this->assertEquals(['charset' => 'utf-8'], $datauri->parameters());
    }

    public function testCreateStaticFactoryMinimal(): void
    {
        $datauri = DataUri::create('data');
        $this->assertEquals('data', $datauri->data());
        $this->assertNull($datauri->mediaType());
        $this->assertEquals([], $datauri->parameters());
    }

    public function testCreateStaticFactoryWithMediaTypeEnum(): void
    {
        $datauri = DataUri::create('data', MediaType::IMAGE_PNG);
        $this->assertEquals('image/png', $datauri->mediaType());
    }

    public function testCreateBase64EncodedStaticFactory(): void
    {
        $datauri = DataUri::createBase64Encoded('hello', 'text/plain', ['charset' => 'utf-8']);
        $this->assertInstanceOf(DataUri::class, $datauri);
        $this->assertEquals(base64_encode('hello'), $datauri->data());
        $this->assertEquals('text/plain', $datauri->mediaType());
        $this->assertEquals(['charset' => 'utf-8'], $datauri->parameters());
        $this->assertStringContainsString('base64', $datauri->toString());
    }

    public function testCreateBase64EncodedMinimal(): void
    {
        $datauri = DataUri::createBase64Encoded('data');
        $this->assertEquals(base64_encode('data'), $datauri->data());
        $this->assertNull($datauri->mediaType());
    }

    public function testDebugInfo(): void
    {
        $datauri = new DataUri('test-data', 'image/jpeg');
        $debug = $datauri->__debugInfo();
        $this->assertArrayHasKey('mediaType', $debug);
        $this->assertArrayHasKey('size', $debug);
        $this->assertEquals('image/jpeg', $debug['mediaType']);
        $this->assertEquals(9, $debug['size']);
    }

    public function testDebugInfoWithoutMediaType(): void
    {
        $datauri = new DataUri('abc');
        $debug = $datauri->__debugInfo();
        $this->assertNull($debug['mediaType']);
        $this->assertEquals(3, $debug['size']);
    }
}
