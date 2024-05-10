<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use Intervention\Image\Config;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Config::class)]
final class ConfigTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        $config = new Config();
        $this->assertInstanceOf(Config::class, $config);

        $this->assertTrue($config->option('autoOrientation'));
        $this->assertTrue($config->option('decodeAnimation'));
        $this->assertEquals('ffffff00', $config->option('blendingColor'));

        $config = new Config(
            autoOrientation: false,
            decodeAnimation: false,
            blendingColor: 'f00',
        );
        $this->assertInstanceOf(Config::class, $config);

        $this->assertFalse($config->option('autoOrientation'));
        $this->assertFalse($config->option('decodeAnimation'));
        $this->assertEquals('f00', $config->option('blendingColor'));
    }

    public function testGetSetOptions(): void
    {
        $config = new Config();
        $this->assertTrue($config->option('autoOrientation'));
        $this->assertTrue($config->option('decodeAnimation'));
        $this->assertEquals('ffffff00', $config->option('blendingColor'));

        $result = $config->setOptions(
            autoOrientation: false,
            decodeAnimation: false,
            blendingColor: 'f00',
        );

        $this->assertFalse($config->option('autoOrientation'));
        $this->assertFalse($config->option('decodeAnimation'));
        $this->assertEquals('f00', $config->option('blendingColor'));

        $this->assertFalse($result->option('autoOrientation'));
        $this->assertFalse($result->option('decodeAnimation'));
        $this->assertEquals('f00', $result->option('blendingColor'));

        $result = $config->setOption('blendingColor', '000');

        $this->assertFalse($config->option('autoOrientation'));
        $this->assertFalse($config->option('decodeAnimation'));
        $this->assertEquals('000', $config->option('blendingColor'));

        $this->assertFalse($result->option('autoOrientation'));
        $this->assertFalse($result->option('decodeAnimation'));
        $this->assertEquals('000', $result->option('blendingColor'));

        $this->assertNull($config->option('unknown'));
        $this->assertEquals('test', $config->option('unknown', 'test'));
    }
}
