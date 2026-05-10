# Size::dimensions() Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add `Size::dimensions(): array` so users can destructure size into `[int, int]` via `[$w, $h] = $image->size()->dimensions();`, resolving [Intervention/image#1480](https://github.com/Intervention/image/issues/1480) without breaking backwards compatibility.

**Architecture:** Single new method on `Size` + matching interface entry on `SizeInterface`. No existing behaviour changes. Companion Claude CI workflow lives on a separate fork-only branch and is NOT included in the upstream PR.

**Tech Stack:** PHP 8.3, PHPUnit 12 (attributes), PHPStan 2.1, PHP_CodeSniffer 4. Existing tests in `tests/Unit/SizeTest.php` already use `#[CoversClass(Size::class)]` and `BaseTestCase`.

**Spec:** `docs/superpowers/specs/2026-05-11-size-dimensions-design.md`

---

## File Map

| File | Action | Responsibility |
|------|--------|----------------|
| `src/Interfaces/SizeInterface.php` | Modify | Declare `dimensions(): array` |
| `src/Size.php` | Modify | Implement `dimensions()` |
| `tests/Unit/SizeTest.php` | Modify | Add 4 new test methods |
| `.github/workflows/claude.yml` | Create (fork-only branch) | Claude GitHub Action — NOT in upstream PR |

## Branch Strategy

- Upstream PR branch: `fix/1480-size-dimensions` from `develop`. Contains library + test changes only.
- Fork-only CI branch: `ops/claude-ci` from `main` (or `develop` if fork has no `main`). Contains only `.github/workflows/claude.yml`.

---

## Task 1: Set up the feature branch and verify baseline

**Files:** none modified — branch setup only.

- [ ] **Step 1: Confirm we are at the repo root and on `develop`**

```bash
cd C:/image
git status
git rev-parse --abbrev-ref HEAD
```

Expected: `develop` and a clean working tree (the `.claude/` untracked dir is fine; ignore it).

- [ ] **Step 2: Create the feature branch off `develop`**

```bash
git checkout -b fix/1480-size-dimensions
```

Expected: `Switched to a new branch 'fix/1480-size-dimensions'`.

- [ ] **Step 3: Install dependencies**

```bash
composer install
```

Expected: dependencies install cleanly, no errors.

- [ ] **Step 4: Run the existing test suite to confirm green baseline**

```bash
vendor/bin/phpunit
```

Expected: all tests pass. If any test already fails on `develop`, stop and note the failures — do not proceed.

- [ ] **Step 5: Commit the plan + spec to the feature branch**

```bash
git add docs/superpowers/
git commit -m "docs: add design and plan for Size::dimensions() (#1480)"
```

---

## Task 2: Write the failing test for `dimensions()` returning `[int, int]`

**Files:**
- Modify: `tests/Unit/SizeTest.php` (add new test method before the closing `}`)

- [ ] **Step 1: Add the failing test method**

Open `tests/Unit/SizeTest.php` and add this method inside the `SizeTest` class (place it after `testConstructorZeroDimensions` for grouping, but any position inside the class works):

```php
    public function testDimensionsReturnsWidthAndHeightAsInts(): void
    {
        $size = new Size(100, 50);
        $dimensions = $size->dimensions();

        $this->assertSame([100, 50], $dimensions);
        $this->assertIsInt($dimensions[0]);
        $this->assertIsInt($dimensions[1]);
    }
```

- [ ] **Step 2: Run only the new test to confirm it fails for the right reason**

```bash
vendor/bin/phpunit --filter testDimensionsReturnsWidthAndHeightAsInts
```

Expected: ERROR/FAIL with `Call to undefined method Intervention\Image\Size::dimensions()`.

- [ ] **Step 3: Do NOT commit yet — go to Task 3 (interface) before implementing.**

---

## Task 3: Declare `dimensions()` on `SizeInterface`

**Files:**
- Modify: `src/Interfaces/SizeInterface.php`

- [ ] **Step 1: Add the interface method declaration**

In `src/Interfaces/SizeInterface.php`, add this method immediately after the `height(): int` declaration (currently lines 20-23):

```php
    /**
     * Get the size's width and height as a two-element list of integers.
     *
     * Intended for array destructuring:
     *
     *     [$width, $height] = $image->size()->dimensions();
     *
     * @return array{0: int, 1: int}
     */
    public function dimensions(): array;
```

- [ ] **Step 2: Run static analysis (if PHPStan is configured) to confirm `Size` is now flagged as missing the method**

```bash
vendor/bin/phpstan analyse src --level=max --no-progress
```

Expected: error on `Size` like `Class Intervention\Image\Size contains 1 abstract method (dimensions) and must be declared abstract...` OR `... does not implement method dimensions()`. If PHPStan is not configured, skip this step.

---

## Task 4: Implement `dimensions()` on `Size`

**Files:**
- Modify: `src/Size.php`

- [ ] **Step 1: Add the implementation**

In `src/Size.php`, add this method right after the `setSize()` method (currently ends around line 65). The method must come before `setWidth()` so related methods stay grouped:

```php
    /**
     * {@inheritdoc}
     *
     * @see SizeInterface::dimensions()
     *
     * @return array{0: int, 1: int}
     */
    public function dimensions(): array
    {
        return [$this->width(), $this->height()];
    }
```

- [ ] **Step 2: Run the failing test from Task 2 again**

```bash
vendor/bin/phpunit --filter testDimensionsReturnsWidthAndHeightAsInts
```

Expected: PASS (1 test, 3 assertions).

- [ ] **Step 3: Commit interface + implementation + first test together**

```bash
git add src/Interfaces/SizeInterface.php src/Size.php tests/Unit/SizeTest.php
git commit -m "feat(size): add dimensions() returning [int, int] (#1480)"
```

---

## Task 5: Add the destructuring test

**Files:**
- Modify: `tests/Unit/SizeTest.php`

- [ ] **Step 1: Add the destructuring test method**

In `tests/Unit/SizeTest.php`, add after the previous new test:

```php
    public function testDimensionsEnablesArrayDestructuring(): void
    {
        [$width, $height] = (new Size(120, 80))->dimensions();

        $this->assertSame(120, $width);
        $this->assertSame(80, $height);
    }
```

- [ ] **Step 2: Run the test — it should pass because Task 4 already implemented `dimensions()`**

```bash
vendor/bin/phpunit --filter testDimensionsEnablesArrayDestructuring
```

Expected: PASS (1 test, 2 assertions).

- [ ] **Step 3: Commit**

```bash
git add tests/Unit/SizeTest.php
git commit -m "test(size): cover dimensions() array destructuring"
```

---

## Task 6: Add the setter-reflection test

**Files:**
- Modify: `tests/Unit/SizeTest.php`

- [ ] **Step 1: Add the test**

```php
    public function testDimensionsReflectsSetWidthAndSetHeight(): void
    {
        $size = new Size(100, 50);
        $size->setWidth(200)->setHeight(150);

        $this->assertSame([200, 150], $size->dimensions());
    }
```

- [ ] **Step 2: Run it**

```bash
vendor/bin/phpunit --filter testDimensionsReflectsSetWidthAndSetHeight
```

Expected: PASS.

- [ ] **Step 3: Commit**

```bash
git add tests/Unit/SizeTest.php
git commit -m "test(size): cover dimensions() after setWidth/setHeight"
```

---

## Task 7: Add the BC-guard test for `ArrayAccess`

**Files:**
- Modify: `tests/Unit/SizeTest.php`

This test exists specifically to prove the PR does NOT change what `$size[0]` returns. Without it, a future contributor could silently break BC.

- [ ] **Step 1: Verify the existing `use` for `PointInterface`**

Check the `use` block at the top of `tests/Unit/SizeTest.php`. If `Intervention\Image\Interfaces\PointInterface` is not already imported, add it:

```php
use Intervention\Image\Interfaces\PointInterface;
```

- [ ] **Step 2: Add the BC-guard test**

```php
    public function testArrayAccessStillReturnsCornerPoints(): void
    {
        // BC guard for #1480: Size extends Polygon, so $size[0..3] must
        // continue to return the four corner Points (top-left, top-right,
        // bottom-right, bottom-left). dimensions() is the new, non-breaking
        // way to get [width, height].
        $size = new Size(10, 20);

        $this->assertInstanceOf(PointInterface::class, $size[0]);
        $this->assertSame(0, $size[0]->x());
        $this->assertSame(0, $size[0]->y());
        $this->assertSame(10, $size[1]->x());
        $this->assertSame(0, $size[1]->y());
    }
```

- [ ] **Step 3: Run the test**

```bash
vendor/bin/phpunit --filter testArrayAccessStillReturnsCornerPoints
```

Expected: PASS.

- [ ] **Step 4: Commit**

```bash
git add tests/Unit/SizeTest.php
git commit -m "test(size): guard ArrayAccess BC for #1480"
```

---

## Task 8: Run the full quality gate

**Files:** none modified — verification only.

- [ ] **Step 1: Run the full test suite**

```bash
vendor/bin/phpunit
```

Expected: all tests pass, including the four new ones.

- [ ] **Step 2: Run PHPStan**

```bash
vendor/bin/phpstan analyse --no-progress
```

Expected: no new errors. If PHPStan reports issues in the modified files, fix them before moving on. If `phpstan.neon` is not configured, skip this step.

- [ ] **Step 3: Run PHP_CodeSniffer**

```bash
vendor/bin/phpcs
```

Expected: no violations in modified files. If `phpcs.xml`/`phpcs.xml.dist` is not configured, skip.

- [ ] **Step 4: If lint fails, fix and commit the fix**

```bash
vendor/bin/phpcbf
git diff
git add -u
git commit -m "style: apply phpcbf to size changes"
```

---

## Task 9: Push the branch and open the upstream PR

**Files:** none — git/gh operations only.

- [ ] **Step 1: Confirm `gh` CLI is installed**

```bash
gh --version
```

If missing, install: `winget install GitHub.cli`, then `gh auth login`.

- [ ] **Step 2: Push the branch to the fork**

```bash
git push -u origin fix/1480-size-dimensions
```

Expected: branch published; gh prints a URL.

- [ ] **Step 3: Open the PR against `Intervention/image:develop`**

```bash
gh pr create \
  --repo Intervention/image \
  --base develop \
  --head Abdooo2235:fix/1480-size-dimensions \
  --title "Add Size::dimensions() for destructuring (fixes #1480)" \
  --body "$(cat <<'EOF'
## Summary

Closes #1480.

Adds `Size::dimensions(): array{0: int, 1: int}` so consumers can destructure
size into a `[width, height]` int pair:

```php
[$width, $height] = $image->size()->dimensions();
```

## Why not change `[$w, $h] = $image->size()` directly?

The literal syntax in the original issue would require changing
`ArrayAccess::offsetGet` on `Size`. That offset is currently the way
`Polygon` (Size's parent) exposes the four corner `Point` objects, and
`Size::setWidth()`/`setHeight()` rely on it internally
(`$this[1]->setX(...)`, etc.). Repurposing those offsets to return ints
would be a breaking change for every caller and for the class's own setters.

`dimensions()` is the non-breaking alternative: same ergonomics for
destructuring, zero risk for existing code.

## Tests

Added to `tests/Unit/SizeTest.php`:

- `testDimensionsReturnsWidthAndHeightAsInts`
- `testDimensionsEnablesArrayDestructuring`
- `testDimensionsReflectsSetWidthAndSetHeight`
- `testArrayAccessStillReturnsCornerPoints` (explicit BC guard)

`vendor/bin/phpunit` passes locally.

## Notes

- `SizeInterface` updated to include the new method.
- No changes to `Polygon` or `ArrayAccess` semantics.
EOF
)"
```

Expected: gh prints the PR URL. Open it in a browser to verify the description rendered.

---

## Task 10: Fork-only Claude CI workflow (separate branch)

This task is NOT part of the upstream PR. It lives on its own branch on your fork.

**Files:**
- Create: `.github/workflows/claude.yml`

- [ ] **Step 1: Create the fork-only branch off the latest `develop`**

```bash
git checkout develop
git pull origin develop
git checkout -b ops/claude-ci
```

- [ ] **Step 2: Create the workflow file**

Write `.github/workflows/claude.yml` with this exact content:

```yaml
name: Claude

on:
  issue_comment:
    types: [created]
  pull_request_review_comment:
    types: [created]
  pull_request_review:
    types: [submitted]
  issues:
    types: [opened, assigned]

jobs:
  claude:
    if: |
      (github.event_name == 'issue_comment' && contains(github.event.comment.body, '@claude')) ||
      (github.event_name == 'pull_request_review_comment' && contains(github.event.comment.body, '@claude')) ||
      (github.event_name == 'pull_request_review' && contains(github.event.review.body, '@claude')) ||
      (github.event_name == 'issues' && (contains(github.event.issue.body, '@claude') || contains(github.event.issue.title, '@claude')))
    runs-on: ubuntu-latest
    permissions:
      contents: write
      pull-requests: write
      issues: write
      id-token: write
    steps:
      - name: Checkout repository
        uses: actions/checkout@v4
        with:
          fetch-depth: 1

      - name: Run Claude Code
        uses: anthropics/claude-code-action@v1
        with:
          anthropic_api_key: ${{ secrets.ANTHROPIC_API_KEY }}
```

- [ ] **Step 3: Commit and push**

```bash
git add .github/workflows/claude.yml
git commit -m "ci: add Claude GitHub Action (fork-only)"
git push -u origin ops/claude-ci
```

- [ ] **Step 4: Configure the secret on the fork**

Open `https://github.com/Abdooo2235/image/settings/secrets/actions` in a browser and add a repository secret named `ANTHROPIC_API_KEY` with your API key. Without it, the workflow will fail.

- [ ] **Step 5: Verify the workflow appears**

```bash
gh workflow list --repo Abdooo2235/image
```

Expected: a `Claude` workflow appears.

- [ ] **Step 6: Confirm the upstream PR branch does NOT include this file**

```bash
git checkout fix/1480-size-dimensions
git ls-files .github/workflows/claude.yml
```

Expected: empty output (file is not on this branch). If the file appears, remove it: `git rm .github/workflows/claude.yml && git commit -m "chore: remove fork-only ci from pr branch"`.

---

## Definition of Done

- [ ] `Size::dimensions()` exists on the class and on `SizeInterface`.
- [ ] Four new tests pass; full `vendor/bin/phpunit` is green.
- [ ] PHPStan and PHP_CodeSniffer (if configured) report no new errors.
- [ ] PR opened against `Intervention/image:develop`; URL captured.
- [ ] `ops/claude-ci` branch on the fork contains `.github/workflows/claude.yml`; the upstream PR branch does NOT contain it.
- [ ] `ANTHROPIC_API_KEY` secret set on `Abdooo2235/image`.
