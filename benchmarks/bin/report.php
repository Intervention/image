<?php

declare(strict_types=1);

/**
 * Compare benchmark result files and print a markdown table.
 *
 *   php bin/report.php results/debian-before.json results/debian-after.json [...]
 *
 * The first file is the reference; following files get a delta column.
 */

$files = array_slice($argv, 1);
if ($files === []) {
    fwrite(STDERR, "usage: php bin/report.php <ref.json> [other.json ...]\n");
    exit(1);
}

$runs = [];
foreach ($files as $file) {
    $data = json_decode((string) file_get_contents($file), true, flags: JSON_THROW_ON_ERROR);
    $rows = [];
    foreach ($data['results'] as $r) {
        $rows[$r['driver'] . '|' . $r['scenario']] = $r;
    }
    $runs[basename($file, '.json')] = ['env' => $data['env'], 'rows' => $rows];
}

$labels = array_keys($runs);
$ref = $runs[$labels[0]];

foreach ($runs as $label => $run) {
    printf("- **%s**: %s, PHP %s, %s, libvips %s, %d CPUs\n", $label, $run['env']['os'], $run['env']['php'], $run['env']['imagemagick'], $run['env']['vips'], $run['env']['cpus']);
}
echo "\n";

$fmt = fn(array $r): string => isset($r['error'])
    ? 'error'
    : ($r['ops'] > 1
        ? sprintf('%.2f ms (%.1f µs/op)', $r['median_ms'], 1000 * $r['median_ms'] / $r['ops'])
        : sprintf('%.2f ms', $r['median_ms']));

$header = array_merge(['driver', 'scenario'], $labels);
echo '| ' . implode(' | ', $header) . " |\n";
echo '|' . str_repeat(' --- |', count($header)) . "\n";

foreach ($ref['rows'] as $key => $r) {
    $cells = [$r['driver'], $r['scenario'], $fmt($r)];
    foreach (array_slice($labels, 1) as $label) {
        $o = $runs[$label]['rows'][$key] ?? null;
        if ($o === null) {
            $cells[] = '–';
            continue;
        }
        $cell = $fmt($o);
        if (!isset($o['error'], $r['error']) && isset($o['median_ms'], $r['median_ms'])) {
            $delta = 100 * ($o['median_ms'] - $r['median_ms']) / $r['median_ms'];
            $cell .= sprintf(abs($delta) >= 5 ? ' **%+.0f%%**' : ' %+.0f%%', $delta);
        }
        $cells[] = $cell;
    }
    echo '| ' . implode(' | ', $cells) . " |\n";
}
