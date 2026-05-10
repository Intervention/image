# Design: `Size::dimensions()` for array destructuring

**Issue:** [Intervention/image#1480](https://github.com/Intervention/image/issues/1480)
**Author:** Abdooo2235 (fork) — targeting upstream `Intervention/image:develop`
**Date:** 2026-05-11
**Status:** Approved (brainstorm)

---

## 1. Problem

Users want to destructure an image size into `width` and `height`:

```php
[$width, $height] = $image->size(); // expected: ints
```

Today this returns the four corner `Point` objects of the rectangle, because `Size` extends `Polygon`, which implements `ArrayAccess::offsetGet` returning the indexed corner point.

The existing override of `IteratorAggregate::getIterator` in `Size` does yield `[width, height]`, but PHP's array-destructuring syntax (`[$a, $b] = $obj`) resolves via `ArrayAccess`, not the iterator — so the override does not help destructuring.

## 2. Constraint: backwards compatibility is non-negotiable

This change targets upstream merge. Changing what `$size[0]` returns would break every caller (including `Size::setWidth`/`setHeight` which use `$this[0..3]` internally — see `src/Size.php:74-87`). Therefore we cannot redefine `offsetGet` semantics on `Size`.

This means the literal syntax from the issue (`[$w, $h] = $image->size()`) **cannot be supported** without a BC break. The PR will say so explicitly and offer a pragmatic alternative.

## 3. Solution

Add one method to `SizeInterface` and implement it on `Size`:

```php
/**
 * Return [width, height] as a two-element list of ints.
 *
 * Intended for array destructuring:
 *
 *     [$width, $height] = $image->size()->dimensions();
 *
 * @return array{0: int, 1: int}
 */
public function dimensions(): array;
```

Implementation:

```php
public function dimensions(): array
{
    return [$this->width(), $this->height()];
}
```

That is the entire library change. No other file in `src/` is touched. `width()` and `height()` already return `int`.

## 4. Tests

New file: `tests/Unit/SizeTest.php` (or new methods on the existing file if present).

| # | Test | Assertion |
|---|------|-----------|
| 1 | `testDimensionsReturnsWidthAndHeightAsInts` | `(new Size(100, 50))->dimensions() === [100, 50]`, both elements are `int`. |
| 2 | `testDimensionsEnablesArrayDestructuring` | `[$w, $h] = (new Size(120, 80))->dimensions();` then `$w === 120 && $h === 80`. |
| 3 | `testDimensionsReflectsSetWidthAndSetHeight` | After `setWidth(200)->setHeight(150)`, `dimensions() === [200, 150]`. |
| 4 | `testArrayAccessStillReturnsCornerPoints` (BC guard) | `(new Size(10, 10))[0] instanceof PointInterface`; coordinates equal the top-left corner. |

Baseline: `composer install && vendor/bin/phpunit` must pass before and after.

## 5. Documentation

- PHPDoc on `dimensions()` in both `SizeInterface` and `Size` with `@return array{0: int, 1: int}` so PHPStan/Psalm consumers see the precise shape.
- No README change. Intervention/image keeps usage docs on a separate documentation site; that update is out of scope for this PR.

## 6. Claude CI/CD on the fork (not in upstream PR)

A separate, fork-only addition: `.github/workflows/claude.yml` using `anthropics/claude-code-action`.

- Trigger: `issue_comment` and `pull_request_review_comment` events containing `@claude`.
- Secret: `ANTHROPIC_API_KEY` configured in fork repo settings.
- Permissions: `contents: write`, `pull-requests: write`, `issues: write`.
- **Lives only on the fork.** Committed to a separate branch (`ops/claude-ci`) on `Abdooo2235/image`. **Not** included in the upstream PR — Intervention/image maintainers would reasonably reject a workflow that consumes their API budget.

## 7. Branch strategy

| Branch | Base | Purpose |
|--------|------|---------|
| `fix/1480-size-dimensions` | `Intervention/image:develop` (via fork) | Upstream PR — contains only the library + test changes. |
| `ops/claude-ci` | `Abdooo2235/image:main` | Fork-only Claude GitHub Action workflow. |

## 8. PR description (draft for upstream)

- **Title:** `Add Size::dimensions() for destructuring (fixes #1480)`
- **Body:**
  - Restate the issue.
  - Explain why literal `[$w, $h] = $image->size()` would require a BC break (corner-point access via `ArrayAccess`).
  - Present `dimensions()` as the non-breaking alternative; show a usage example.
  - Note the new tests, including the BC-guard test.
  - Confirm `vendor/bin/phpunit` passes locally.
  - Link the issue.

## 9. Out of scope

- Refactoring `Size` to not extend `Polygon`.
- Changing `Polygon`/`ArrayAccess` semantics.
- Adding magic-method tricks (e.g. `__get`) to fake destructuring.
- Documentation-site updates (separate repo).

## 10. Risks and mitigation

| Risk | Mitigation |
|------|------------|
| Maintainer wants literal `[$w, $h] = $image->size()` and rejects the PR. | PR body acknowledges this and explains the BC tradeoff explicitly; offer to also expose a static analyser-friendly `getIterator` annotation if requested. |
| Test naming conflicts with existing `SizeTest.php`. | Read existing file first; either append methods or rename. |
| `develop` branch protections require additional CI to pass. | Check `.github/workflows/` for existing CI before pushing; do not disable it. |

## 11. Acceptance criteria

- [ ] `Size::dimensions()` exists, is in `SizeInterface`, and returns `[int, int]`.
- [ ] All four new tests pass.
- [ ] Full existing test suite passes unchanged.
- [ ] PHPStan/Psalm (if configured in repo) reports no new errors.
- [ ] Upstream PR opened against `Intervention/image:develop` from `Abdooo2235:fix/1480-size-dimensions`.
- [ ] Fork-only `.github/workflows/claude.yml` lives on `ops/claude-ci`, not on the PR branch.
