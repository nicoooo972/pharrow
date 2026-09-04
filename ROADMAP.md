# Roadmap

This document stands in for issues while the repository is not yet pushed to
GitHub. Each ticket below will become a GitHub issue as is (title,
description, acceptance criteria) once that happens.

## Project goal

A test runner compatible with the PHPUnit test writing format (classes that
extend `TestCase`, `testXxx()` methods, `assertEquals()`,
`setUp()`/`tearDown()`, `@dataProvider`, and so on), but with no dependency
on the `phpunit/phpunit` composer package. Tests run through our own minimal
PHP harness, always launched by the real `php` binary (no homemade VM or
interpreter; see the "Long term ambition" section below).

The existing Rust orchestrator (discovering `*Test.php` files, splitting them
into balanced batches by duration, running them in parallel, aggregating
results) stays as it is. It doesn't need to know anything about PHPUnit, it
just needs a PHP process to produce a report it can parse per file.

## Scope decision (2026-09-03)

Two levels were considered:
1. Chosen: replace only the `phpunit/phpunit` package with a homemade PHP
   harness, keeping `php` as the execution engine.
2. Set aside for now: also replace `php` itself with an interpreter written
   in Rust. No Rust project has ever reached actual PHP execution (Tagua VM
   was archived in 2019, still at the parser stage; PXP was abandoned in
   2024 when its solo maintainer ran out of steam). This option remains a
   possible long term goal if the project attracts contributors, but it
   shouldn't block shipping something useful first.

## Milestones

### v0.1.0, Minimal viable harness
First PHPUnit like test running end to end with `phpunit/phpunit` not
installed.

- [x] **#1 Pick the final project name.**

- [ ] **#2 Homemade `TestCase` class** (PHP). Minimal properties/methods:
  constructor, `setUp()`/`tearDown()` called automatically around each test,
  a `run()` method that executes a given `testXxx` method and captures
  success, failure, or exception.
- [ ] **#3 Basic assertion set** (PHP): `assertEquals`, `assertSame`,
  `assertTrue`, `assertFalse`, `assertNull`, `assertNotNull`, `assertCount`,
  `assertInstanceOf`. Each failed assertion throws a dedicated exception
  (e.g. `AssertionFailedException`) carrying a message, file, and line.
- [ ] **#4 Harness autoloader/bootstrap.** A PHP script (invoked by the Rust
  side instead of `vendor/bin/phpunit`) that loads the target project's
  composer autoload (PSR-4, so test classes and application code can be
  loaded), includes the test file received as an argument, discovers
  `public function testXxx()` methods through reflection, and runs them one
  by one through `TestCase::run()`.
- [ ] **#5 Machine readable report format.** The harness must produce a
  report the Rust binary can parse. Either reuse the JUnit XML format
  already consumed by `parse_junit()` in `main.rs` to avoid touching the
  orchestrator, or define a simpler format (line delimited JSON) if that
  turns out easier to generate on the PHP side. To be decided when #4/#5
  are actually being built.
- [ ] **#6 Update `main.rs`** to invoke the new harness (bootstrap script
  path) instead of looking for `vendor/bin/phpunit`. Remove
  `find_phpunit_bin()` once the harness is in place.
- [ ] **#7 End to end test.** A minimal `ExampleTest.php` (one class, one
  `testXxx` method, one assertion) that passes, plus one that fails on
  purpose, checked manually and then through a Rust integration test
  (`tests/tests.rs`, see #12).

### v0.2.0, Common functional coverage
- [ ] **#8 `@dataProvider` / `#[DataProvider]`.** Support both syntaxes (the
  historical docblock annotation and the PHP 8 attribute). A test with a
  provider must show up as N distinct cases in the report.
- [ ] **#9 `expectException()` / `expectExceptionMessage()`.**
- [ ] **#10 Skipped/incomplete tests** (`markTestSkipped`,
  `markTestIncomplete`).
- [ ] **#11 Extended assertions**: `assertStringContainsString`,
  `assertArrayHasKey`, `assertGreaterThan`/`assertLessThan`,
  `assertMatchesRegularExpression`, `assertJsonStringEqualsJsonString`.
  Prioritize the ones actually used in mdf-api-full (run `grep -r
  "->assert" tests/` on that project to get a real list instead of
  guessing).

### v0.3.0, Reliability and ergonomics
- [ ] **#12 Rust integration tests** (`tests/tests.rs` plus fake PHP
  fixtures under `tests/testenv/`), following the fd convention found
  during research. No real Symfony project is needed to test the runner
  itself.
- [ ] **#13 Error messages with context**: a readable diff on
  `assertEquals` (expected vs actual), not just "assertion failed".
- [ ] **#14 User documentation**: README with a minimal example, list of
  supported vs unsupported assertions compared to PHPUnit, so contributors
  know where to help.

### v1.0.0, Ready for external contribution
- [ ] **#15 GitHub Actions CI** (fmt, clippy, tests; see the research
  section for the fd `CICD.yml` model).
- [ ] **#16 Finalized CONTRIBUTING.md**, CHANGELOG.md started (conventional
  commits plus `release-plz`, recommended by the research to automate
  versioning and the CHANGELOG in CI).
- [ ] **#17 Dogfooding on mdf-api-full**: replace the current use of
  `vendor/bin/phpunit` with this harness on a real subset of functional
  tests, and measure the gap in assertion coverage.

## Long term ambition (out of current scope, not planned)

A PHP interpreter written in Rust, so the project no longer depends on the
`php` binary at all. Deliberately left out of the milestones until v1.0.0 is
reached. This is a project of a completely different scale (comparable to
`php-src`/HHVM according to the research done), and the documented risk from
previous attempts (PXP, Tagua VM) is a solo maintainer running out of
energy. Better to ship something useful and contributable first, before
tackling this, if it ever happens.
