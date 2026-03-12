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
        bool $base64,
        string $result,
    ): void {
        $datauri = new DataUri($data, $mediaType, $parameters, $base64);
        $this->assertEquals($result, $datauri->toString());
        $this->assertEquals($result, (string) $datauri);
    }

    #[DataProvider('toStringDataProvider')]
    public function testCreateStaticFactory(
        string $data,
        ?string $mediaType,
        array $parameters,
        bool $base64,
        string $result,
    ): void {
        $datauri = DataUri::create($data, $mediaType, $parameters, $base64);
        $this->assertInstanceOf(DataUri::class, $datauri);
        $this->assertEquals($data, $datauri->data());
        $this->assertEquals($mediaType, $datauri->mediaType());
        $this->assertEquals($parameters, $datauri->parameters());
        $this->assertEquals($result, (string) $datauri);
    }

    #[DataProvider('toStringDataProvider')]
    public function testCreateParse(
        string $data,
        ?string $mediaType,
        array $parameters,
        bool $base64,
        string $result,
    ): void {
        $this->assertEquals($result, DataUri::create($data, $mediaType, $parameters, $base64)->toString());
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
            'data:text/plain;charset=utf-8;base64,Zm9v'
        ];

        yield [
            'hello',
            'text/plain',
            ['charset' => 'utf-8'],
            true,
            'data:text/plain;charset=utf-8;base64,aGVsbG8=',
        ];

        yield [
            'hello',
            'text/plain',
            [],
            false,
            'data:text/plain,hello',
        ];
    }

    #[DataProviderExternal(DataUriDataProvider::class, 'validDataUris')]
    public function testParse(string $dataUriScheme, string $resultData): void
    {
        $datauri = DataUri::parse($dataUriScheme);
        $this->assertInstanceOf(DataUri::class, $datauri);
        $this->assertEquals($resultData, $datauri->data());
    }

    #[DataProviderExternal(DataUriDataProvider::class, 'invalidDataUris')]
    public function testParseInvalid(string $input, string $exception): void
    {
        $this->expectException($exception);
        DataUri::parse($input);
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
        $datauri = DataUri::create('hello', 'text/plain', ['charset' => 'utf-8'], base64: true);
        $this->assertInstanceOf(DataUri::class, $datauri);
        $this->assertEquals('data:text/plain;charset=utf-8;base64,aGVsbG8=', $datauri->toString());
        $this->assertEquals('hello', $datauri->data());
        $this->assertEquals('text/plain', $datauri->mediaType());
        $this->assertEquals(['charset' => 'utf-8'], $datauri->parameters());
    }

    public function testCreateBase64EncodedMinimal(): void
    {
        $datauri = DataUri::create('test123', base64: true);
        $this->assertEquals('data:;base64,dGVzdDEyMw==', $datauri->toString());
        $this->assertEquals('test123', $datauri->data());
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
