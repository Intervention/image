#!/bin/sh
# Usage: ./run.sh <label> [extra bench.php args]
# Runs the benchmark sequentially (never in parallel: VIPS is multi-threaded and
# would skew the other run) on Debian then Alpine, writing results/<os>-<label>.json
set -e
cd "$(dirname "$0")"
label="${1:?label required}"; shift
docker compose run --rm -T debian composer install --quiet --no-interaction
docker compose run --rm -T debian php bin/fixtures.php
for os in debian alpine; do
    docker compose run --rm -T "$os" php bin/bench.php --min-time=2 --max-iter=150 --out="results/$os-$label.json" "$@"
done
