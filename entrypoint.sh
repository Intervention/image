#!/bin/sh
set -e

composer install --quiet

exec "$@"
