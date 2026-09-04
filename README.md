<p align="center">pharrow is a fast parallel test runner for PHP. Runs your existing PHPUnit test classes unchanged, with a Rust-powered orchestrator. Pre-alpha, under active development.</p>

<p align="center">
  <img alt="Status" src="https://img.shields.io/badge/status-pre--v0.1.0-orange" />
  <img alt="GitHub commit activity" src="https://img.shields.io/github/commit-activity/m/nicoooo972/pharrow"/>
  <img alt="Github Last Commit" src="https://img.shields.io/github/last-commit/nicoooo972/pharrow"/>
  <img alt="License" src="https://img.shields.io/badge/license-MIT%20OR%20Apache--2.0-blue" />
</p>

<p align="center">
  <a href="ROADMAP.md">Roadmap</a>
  · <a href="https://github.com/nicoooo972/pharrow/issues">Bug reports</a>
  · <a href="CONTRIBUTING.md">Contributing</a>
</p>

---

pharrow lets you write tests the way you already do with PHPUnit classes extending `TestCase`, `testXxx()` methods, `assertEquals()`, `setUp()`/`tearDown()`, `@dataProvider` but runs them without the `phpunit/phpunit` composer package. Execution goes through a minimal PHP harness that we own, always invoked by the real `php` binary (no custom VM or interpreter : see [Long-term ambition](#long-term-ambition) below).

The Rust orchestrator will handle test-file discovery, duration-balanced batching, and parallel execution; it stays agnostic to PHPUnit internals and only needs a PHP process that produces a report it can parse.

## Why

Keep the PHPUnit test-writing syntax teams already know, while removing the dependency on the `phpunit/phpunit` package itself no rewrite required for projects that adopt it. See the [Scope decision](ROADMAP.md#décision-de-scope-2026-09-03) section of the roadmap for the full reasoning, including why a full Rust PHP interpreter is explicitly *not* the short-term goal.

## Status

> ⚠️ **Pre-v0.1.0.** Nothing here is usable yet — no harness, no end-to-end test run. This table exists so contributors can see at a glance what's built vs. planned. Track progress in [ROADMAP.md](ROADMAP.md).

Status legend: ✅ Available · 🚧 In progress · ⬜ Planned

| Feature                           | Status | Feature                         | Status |
| :--------------------------------- | :----: | :------------------------------- | :----: |
| **Test discovery & batching**      | ⬜     | **`@dataProvider` / `#[DataProvider]`** | ⬜     |
| **Parallel execution**             | ⬜     | **`expectException()`**          | ⬜     |
| **`TestCase` harness (PHP)**       | ⬜     | **Skip / incomplete tests**      | ⬜     |
| **Core assertions**                | ⬜     | **Extended assertions**          | ⬜     |
| **Machine-readable report**        | ⬜     | **Readable assertion diffs**     | ⬜     |
| **Composer/PSR-4 autoload**        | ⬜     | **CI (fmt + clippy + tests)**    | ⬜     |

## Quickstart

_Not available yet — no first working end-to-end test run has landed. Follow [ROADMAP.md](ROADMAP.md) milestone v0.1.0 for progress toward the first usable build._

## Documentation

No dedicated docs site yet. For now:

- [ROADMAP.md](ROADMAP.md) : milestones, scope decisions, and the full ticket list
- [CONTRIBUTING.md](CONTRIBUTING.md) : how to contribute

## Long-term ambition

A PHP interpreter written entirely in Rust, to drop the dependency on the `php` binary itself. Deliberately out of scope until v1.0.0 is reached: it's a project of a different scale (comparable to `php-src`/HHVM), and prior attempts (Tagua VM, PXP) stalled from solo-maintainer burnout. Worth revisiting once pharrow has delivered something useful and attracted contributors not before.

## Getting Help

- Open an issue on [GitHub Issues](https://github.com/nicoooo972/pharrow/issues) for bugs or feature requests.
- Check [ROADMAP.md](ROADMAP.md) before asking "is X supported", it tracks exactly what's built vs. planned.

## Contact

- **Bugs**: [GitHub Issues](https://github.com/nicoooo972/pharrow/issues)
- **Contributing**: [CONTRIBUTING.md](CONTRIBUTING.md)

## License

Dual-licensed under [MIT](LICENSE-MIT) or [Apache 2.0](LICENSE-APACHE), at your option.
