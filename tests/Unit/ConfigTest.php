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

        $this->assertTrue($config->autoOrientation);
        $this->assertTrue($config->decodeAnimation);
        $this->assertEquals('ffffff', $config->blendingColor);

        $config = new Config(
            autoOrientation: false,
            decodeAnimation: false,
            blendingColor: 'f00',
        );
        $this->assertInstanceOf(Config::class, $config);

        $this->assertFalse($config->autoOrientation);
        $this->assertFalse($config->decodeAnimation);
        $this->assertEquals('f00', $config->blendingColor);
    }

    public function testGetSetOptions(): void
    {
        $config = new Config();
        $this->assertTrue($config->autoOrientation);
        $this->assertTrue($config->decodeAnimation);
        $this->assertEquals('ffffff', $config->blendingColor);

        $result = $config->setOptions(
            autoOrientation: false,
            decodeAnimation: false,
            blendingColor: 'f00',
        );

        $this->assertFalse($config->autoOrientation);
        $this->assertFalse($config->decodeAnimation);
        $this->assertEquals('f00', $config->blendingColor);

        $this->assertFalse($result->autoOrientation);
        $this->assertFalse($result->decodeAnimation);
        $this->assertEquals('f00', $result->blendingColor);

        $result = $config->setOption('blendingColor', '000');

        $this->assertFalse($config->autoOrientation);
        $this->assertFalse($config->decodeAnimation);
        $this->assertEquals('000', $config->blendingColor);

        $this->assertFalse($result->autoOrientation);
        $this->assertFalse($result->decodeAnimation);
        $this->assertEquals('000', $result->blendingColor);
    }
}
