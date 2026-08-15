<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors;

use Generator;
use Intervention\Image\Color;
use Intervention\Image\Colors\Quantizer;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class QuantizerTest extends BaseTestCase
{
    public function testLevelTooLow(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Quantizer(0);
    }

    public function testLevelTooHigh(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Quantizer(1000);
    }

    #[DataProvider('rgb')]
    #[DataProvider('cmyk')]
    public function testQuantizeColor(int $level, ColorInterface $input, ColorInterface $output): void
    {
        $this->assertEquals($output, (new Quantizer($level))->quantizeColor($input));
    }

    public function testQuantizeColors(): void
    {
        $quantizer = new Quantizer(8);
        $colors = [
            Color::rgb(115, 116, 115),
            Color::rgb(125, 127, 128),
            Color::rgb(113, 115, 115),
            Color::rgb(86, 78, 77),
            Color::rgb(83, 88, 94),
            Color::rgb(110, 112, 111),
            Color::rgb(113, 114, 115),
            Color::rgb(97, 100, 106),
            Color::rgb(75, 71, 67),
            Color::rgb(110, 119, 129),
            Color::rgb(88, 95, 104),
            Color::rgb(36, 32, 30),
            Color::rgb(123, 128, 128),
            Color::rgb(47, 48, 49),
            Color::rgb(76, 76, 84),
            Color::rgb(100, 102, 106),
            Color::rgb(122, 130, 141),
            Color::rgb(100, 97, 95),
            Color::rgb(62, 54, 51),
            Color::rgb(87, 92, 98),
            Color::rgb(234, 234, 244),
            Color::rgb(60, 63, 68),
            Color::rgb(99, 106, 115),
            Color::rgb(19, 18, 17),
            Color::rgb(215, 214, 210),
            Color::rgb(134, 136, 134),
            Color::rgb(224, 223, 220),
            Color::rgb(74, 80, 90),
            Color::rgb(75, 78, 80),
            Color::rgb(134, 133, 147),
            Color::rgb(219, 178, 150),
            Color::rgb(234, 234, 233),
            Color::rgb(136, 141, 142),
            Color::rgb(224, 189, 164),
            Color::rgb(209, 175, 147),
            Color::rgb(217, 171, 139),
            Color::rgb(113, 115, 114),
            Color::rgb(241, 243, 252),
            Color::rgb(250, 247, 244),
            Color::rgb(224, 225, 236),
            Color::rgb(29, 24, 23),
            Color::rgb(235, 196, 169),
            Color::rgb(203, 198, 192),
            Color::rgb(205, 203, 198),
            Color::rgb(228, 230, 232),
            Color::rgb(218, 159, 122),
            Color::rgb(216, 183, 159),
            Color::rgb(47, 49, 53),
            Color::rgb(210, 206, 199),
            Color::rgb(200, 193, 186),
            Color::rgb(237, 191, 155),
            Color::rgb(214, 160, 130),
            Color::rgb(37, 36, 40),
            Color::rgb(227, 194, 173),
            Color::rgb(78, 86, 103),
            Color::rgb(222, 165, 130),
            Color::rgb(167, 131, 106),
            Color::rgb(63, 64, 70),
            Color::rgb(149, 151, 152),
            Color::rgb(161, 117, 91),
            Color::rgb(176, 175, 171),
            Color::rgb(150, 80, 54),
            Color::rgb(184, 46, 36),
            Color::rgb(154, 153, 151),
            Color::rgb(217, 214, 211),
            Color::rgb(165, 164, 160),
            Color::rgb(228, 190, 174),
            Color::rgb(192, 187, 181),
            Color::rgb(127, 62, 49),
            Color::rgb(215, 175, 154),
            Color::rgb(191, 186, 180),
            Color::rgb(172, 166, 160),
            Color::rgb(39, 22, 19),
            Color::rgb(157, 99, 71),
            Color::rgb(225, 196, 179),
            Color::rgb(166, 32, 30),
            Color::rgb(91, 87, 84),
            Color::rgb(196, 159, 137),
            Color::rgb(235, 188, 151),
            Color::rgb(223, 220, 216),
            Color::rgb(191, 185, 175),
            Color::rgb(168, 161, 156),
            Color::rgb(185, 137, 111),
            Color::rgb(184, 178, 170),
            Color::rgb(66, 62, 65),
            Color::rgb(151, 96, 69),
            Color::rgb(42, 38, 36),
            Color::rgb(213, 212, 219),
            Color::rgb(232, 174, 136),
            Color::rgb(194, 151, 118),
            Color::rgb(211, 152, 120),
            Color::rgb(154, 107, 83),
            Color::rgb(171, 39, 32),
            Color::rgb(164, 33, 32),
            Color::rgb(144, 141, 138),
            Color::rgb(53, 23, 18),
            Color::rgb(214, 155, 118),
            Color::rgb(63, 68, 78),
            Color::rgb(147, 152, 156),
            Color::rgb(227, 185, 152),
            Color::rgb(84, 33, 24),
            Color::rgb(205, 183, 167),
            Color::rgb(148, 29, 27),
            Color::rgb(159, 121, 102),
            Color::rgb(157, 103, 75),
            Color::rgb(203, 138, 104),
            Color::rgb(105, 61, 47),
            Color::rgb(235, 177, 143),
            Color::rgb(59, 26, 20),
            Color::rgb(166, 119, 98),
            Color::rgb(242, 203, 182),
            Color::rgb(52, 44, 40),
            Color::rgb(203, 202, 209),
            Color::rgb(132, 86, 64),
            Color::rgb(72, 31, 21),
            Color::rgb(128, 128, 126),
            Color::rgb(120, 114, 111),
            Color::rgb(77, 72, 65),
            Color::rgb(230, 227, 223),
            Color::rgb(153, 150, 146),
            Color::rgb(155, 147, 140),
            Color::rgb(243, 209, 192),
            Color::rgb(115, 93, 83),
            Color::rgb(106, 97, 91),
            Color::rgb(115, 115, 114),
            Color::rgb(241, 215, 199),
            Color::rgb(181, 184, 196),
            Color::rgb(166, 108, 79),
            Color::rgb(202, 172, 151),
            Color::rgb(174, 160, 146),
            Color::rgb(72, 63, 59),
            Color::rgb(228, 232, 243),
            Color::rgb(229, 207, 195),
            Color::rgb(238, 193, 164),
            Color::rgb(185, 151, 130),
            Color::rgb(120, 69, 50),
            Color::rgb(129, 114, 101),
            Color::rgb(191, 123, 84),
            Color::rgb(232, 224, 214),
            Color::rgb(98, 69, 59),
            Color::rgb(209, 147, 108),
            Color::rgb(132, 113, 105),
            Color::rgb(187, 124, 90),
            Color::rgb(38, 40, 43),
            Color::rgb(135, 73, 55),
            Color::rgb(200, 160, 127),
            Color::rgb(102, 93, 85),
            Color::rgb(239, 204, 189),
            Color::rgb(130, 142, 154),
            Color::rgb(81, 75, 78),
            Color::rgb(179, 146, 116),
            Color::rgb(119, 110, 101),
            Color::rgb(107, 114, 128),
            Color::rgb(148, 153, 153),
            Color::rgb(98, 81, 73),
            Color::rgb(141, 140, 133),
            Color::rgb(119, 80, 64),
            Color::rgb(205, 164, 148),
            Color::rgb(210, 166, 138),
            Color::rgb(168, 132, 116),
            Color::rgb(106, 81, 66),
            Color::rgb(244, 208, 191),
            Color::rgb(180, 173, 165),
            Color::rgb(165, 157, 169),
            Color::rgb(193, 135, 116),
            Color::rgb(102, 36, 27),
            Color::rgb(183, 157, 133),
            Color::rgb(91, 24, 22),
            Color::rgb(245, 218, 208),
            Color::rgb(133, 39, 32),
            Color::rgb(134, 79, 57),
            Color::rgb(74, 87, 106),
            Color::rgb(197, 187, 195),
            Color::rgb(99, 74, 66),
            Color::rgb(186, 126, 105),
            Color::rgb(249, 242, 239),
            Color::rgb(150, 91, 59),
            Color::rgb(163, 45, 42),
            Color::rgb(203, 165, 145),
            Color::rgb(129, 27, 24),
            Color::rgb(165, 138, 120),
            Color::rgb(182, 33, 29),
            Color::rgb(143, 105, 84),
            Color::rgb(133, 31, 28),
            Color::rgb(201, 204, 215),
            Color::rgb(229, 177, 153),
            Color::rgb(100, 38, 29),
            Color::rgb(201, 141, 114),
            Color::rgb(166, 124, 102),
            Color::rgb(186, 143, 114),
            Color::rgb(127, 72, 54),
            Color::rgb(201, 137, 99),
            Color::rgb(149, 110, 91),
            Color::rgb(148, 85, 68),
            Color::rgb(117, 40, 33),
            Color::rgb(179, 164, 150),
            Color::rgb(129, 99, 82),
            Color::rgb(136, 68, 48),
            Color::rgb(176, 143, 127),
            Color::rgb(50, 51, 54),
            Color::rgb(106, 89, 83),
            Color::rgb(51, 31, 26),
            Color::rgb(169, 115, 81),
            Color::rgb(194, 127, 90),
            Color::rgb(69, 55, 47),
            Color::rgb(143, 71, 48),
            Color::rgb(112, 71, 58),
            Color::rgb(83, 38, 28),
            Color::rgb(161, 88, 59),
            Color::rgb(213, 151, 130),
            Color::rgb(158, 132, 109),
            Color::rgb(231, 230, 229),
            Color::rgb(154, 64, 53),
            Color::rgb(148, 44, 35),
            Color::rgb(93, 29, 21),
            Color::rgb(98, 90, 87),
            Color::rgb(107, 102, 97),
            Color::rgb(174, 101, 83),
            Color::rgb(178, 109, 75),
            Color::rgb(104, 99, 103),
            Color::rgb(183, 113, 90),
            Color::rgb(204, 198, 189),
            Color::rgb(204, 200, 208),
            Color::rgb(201, 144, 129),
            Color::rgb(197, 45, 40),
            Color::rgb(153, 87, 67),
            Color::rgb(71, 66, 63),
            Color::rgb(145, 120, 104),
            Color::rgb(160, 112, 93),
            Color::rgb(166, 149, 136),
            Color::rgb(143, 54, 52),
            Color::rgb(172, 128, 95),
            Color::rgb(240, 190, 169),
            Color::rgb(163, 53, 52),
            Color::rgb(204, 126, 108),
            Color::rgb(25, 27, 28),
            Color::rgb(141, 76, 51),
            Color::rgb(136, 141, 140),
            Color::rgb(46, 39, 35),
            Color::rgb(93, 56, 46),
            Color::rgb(164, 122, 116),
            Color::rgb(80, 51, 41),
            Color::rgb(147, 126, 118),
            Color::rgb(181, 170, 184),
            Color::rgb(189, 131, 124),
            Color::rgb(160, 91, 61),
            Color::rgb(184, 83, 76),
            Color::rgb(86, 58, 53),
            Color::rgb(115, 76, 67),
            Color::rgb(136, 126, 134),
            Color::rgb(205, 140, 108),
            Color::rgb(61, 55, 48),
            Color::rgb(94, 62, 51),
            Color::rgb(217, 152, 119),
            Color::rgb(192, 173, 157),
            Color::rgb(124, 32, 26),
        ];

        $result = $quantizer->quantizeColors($colors)->sortByPresenceDesc();
        $this->assertCount(60, $result);
        $this->assertColor(115, 116, 115, 255, $result[0]);

        // quantization result should not depend on color order
        $result = $quantizer->quantizeColors(array_reverse($colors))->sortByPresenceDesc();
        $this->assertCount(60, $result);
        $this->assertColor(115, 116, 115, 255, $result[0]);
    }

    public function testQuantizeColorsKeepsActualColors(): void
    {
        // the palette must contain the first actual color of each bin
        // instead of the calculated bin center
        $palette = (new Quantizer(16))->quantizeColors([
            Color::rgb(100, 100, 100),
            Color::rgb(101, 101, 101), // same bin as previous color
        ]);

        $this->assertCount(1, $palette);
        $this->assertColor(100, 100, 100, 255, $palette->first());
        $this->assertEquals(2, $palette->totalCount());
    }

    public function testQuantizeColorsGroupsColorsIgnoringAlpha(): void
    {
        // colors that only differ in alpha must share one bin
        $palette = (new Quantizer(16))->quantizeColors([
            Color::rgb(100, 100, 100),
            Color::rgb(100, 100, 100)->withTransparency(0.5),
        ]);

        $this->assertCount(1, $palette);
        $this->assertColor(100, 100, 100, 255, $palette->first());
        $this->assertEquals(2, $palette->totalCount());
    }

    public static function rgb(): Generator
    {
        yield [8, Color::rgb(255, 0, 0), Color::rgb(239, 16, 16)];
        yield [16, Color::rgb(255, 0, 0), Color::rgb(247, 8, 8)];
        yield [32, Color::rgb(255, 0, 0), Color::rgb(251, 4, 4)];
        yield [64, Color::rgb(255, 0, 0), Color::rgb(253, 2, 2)];
        yield [128, Color::rgb(255, 0, 0), Color::rgb(254, 1, 1)];
        yield [256, Color::rgb(255, 0, 0), Color::rgb(255, 0, 0)];
    }

    public static function cmyk(): Generator
    {
        yield [8, Color::cmyk(100, 50, 100, 0), Color::cmyk(94, 56, 94, 6)];
    }
}
