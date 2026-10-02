# Benchmarks

Docker based benchmark suite for the GD, Imagick and libvips drivers, on
Debian and Alpine images. Not part of the distributed package.

Every scenario is warmed up, then repeated for a minimum time; the median
wall time is reported. Scenarios prefixed with `native:` run the same work
with the raw extension only, to expose the overhead of the library.

## Usage

```bash
# run all scenarios on Debian then Alpine, writing results/<os>-<label>.json
./run.sh before

# ... apply changes, then compare
./run.sh after
docker compose run --rm debian php bin/report.php results/debian-before.json results/debian-after.json
```

`bin/bench.php` accepts `--drivers=gd,imagick,vips`, `--filter=<regex>`,
`--min-time=<seconds>` and `--max-iter=<n>`. Never run two benchmarks in
parallel: libvips is multi-threaded and skews the other run. Run before/after
comparisons interleaved, as Docker on a laptop drifts by several percent.

## Tools

| Script | Purpose |
| --- | --- |
| `bin/fixtures.php` | Generates the photo-like fixtures (plasma fractal) into `fixtures/` |
| `bin/bench.php` | Runs the scenarios |
| `bin/report.php` | Compares result files as a markdown table |
| `bin/profile.php` | Profiles a snippet with xhprof (hottest functions by exclusive time) |
| `bin/outputs.php` | Prints hashes of encoded outputs, to check optimizations keep results |
| `bin/equivalence.php` | Dumps the full state of Imagick decoded/modified images, to diff two code versions |
| `bin/rotate0-equivalence.php` | Checks that rotating by 0° keeps the previous behavior |

The `im6` service provides ImageMagick 6, which is covered by the CI matrix
but not by the default images.
