<?php
use Intervention\Image\ImageManager;
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/equivalence-lib.php';
$files = array_merge(glob(__DIR__ . '/../../tests/resources/*.*'), glob(__DIR__ . '/../fixtures/*.*'), glob(__DIR__ . '/../fixtures/edge/*.*'));
$problems = 0; $checked = 0; $fast = 0;
foreach (['ffffff', 'rgba(255, 255, 255, 0)', 'ff000080'] as $bg) {
    // GD: alpha and visible pixels unchanged, transparent index as the old code would set it
    $m = ImageManager::usingDriver(Intervention\Image\Drivers\Gd\Driver::class, backgroundColor: $bg);
    $bgColor = $m->driver->decodeColor($bg);
    foreach ($files as $f) {
        try { $a = $m->decodePath($f); } catch (Throwable) { continue; }
        $b = $m->decodePath($f)->rotate(0);
        foreach ($a as $i => $fa) {
            $ga = $fa->native(); $gb = $b->core()->frame($i)->native(); $checked++;
            $wasFast = imageistruecolor($ga) && ($bgColor->alpha()->value() < .5 || imagecolortransparent($ga) === -1);
            $fast += $wasFast ? 1 : 0;
            $expectedT = $bgColor->alpha()->value() < .5 ? (new Intervention\Image\Drivers\Gd\ColorProcessor())->export($bgColor->toColorspace(Intervention\Image\Colors\Rgb\Colorspace::class)) : -1;
            $err = [];
            if (imagecolortransparent($gb) !== $expectedT) $err[] = 'transparent ' . imagecolortransparent($gb) . " != $expectedT";
            for ($y = 0; $y < imagesy($ga); $y++) for ($x = 0; $x < imagesx($ga); $x++) {
                $pa = imagecolorat($ga, $x, $y); $pb = imagecolorat($gb, $x, $y);
                if (!imageistruecolor($ga)) { $c = imagecolorsforindex($ga, $pa); $pa = ($c['alpha'] << 24) | ($c['red'] << 16) | ($c['green'] << 8) | $c['blue']; }
                // alpha must always match, RGB only matters for pixels that are not fully transparent
                $alphaA = ($pa >> 24) & 0x7F; $alphaB = ($pb >> 24) & 0x7F;
                if ($alphaA !== $alphaB || ($alphaA !== 127 && $pa !== $pb)) { $err[] = sprintf('pixel %d,%d differs (%08x -> %08x)', $x, $y, $pa, $pb); break 2; }
            }
            if ($err) { $problems++; printf("GD  %-22s bg=%-24s frame %d fast=%s: %s\n", basename($f), $bg, $i, $wasFast ? 'y' : 'n', implode('; ', $err)); }
        }
    }
    // Imagick: identical to replaying the old implementation by hand
    $m = ImageManager::usingDriver(Intervention\Image\Drivers\Imagick\Driver::class, backgroundColor: $bg);
    foreach ($files as $f) {
        try { $a = $m->decodePath($f); } catch (Throwable) { continue; }
        $pixel = $m->driver->colorProcessor($a)->export($a->driver()->decodeColor($bg));
        foreach ($a as $frame) { $frame->native()->rotateImage($pixel, 0); $frame->native()->setImagePage(0, 0, 0, 0); }
        $b = $m->decodePath($f)->rotate(0);
        $checked++;
        if (json_encode(frameState($a->core()->native())) !== json_encode(frameState($b->core()->native()))) {
            $problems++; printf("IMG %-22s bg=%s differs\n%s\n%s\n", basename($f), $bg, json_encode(frameState($a->core()->native())), json_encode(frameState($b->core()->native())));
        }
    }
}
printf("checked %d frames/images (%d GD frames took the fast path), problems: %d\n", $checked, $fast, $problems);
