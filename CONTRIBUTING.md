# Contributing

Thanks for wanting to contribute. The project is at its very first steps.
[ROADMAP.md](ROADMAP.md) lists the open tickets by milestone, each with its
own acceptance criteria. That's the best place to start.

## Process

1. Open an issue (or comment on an existing ROADMAP ticket once it's been
   migrated to GitHub issues) before starting any nontrivial work, to avoid
   duplicated effort or a direction that wouldn't fit.
2. A PR must:
   - compile without warnings (`cargo clippy --all-features -- -Dwarnings`)
   - be formatted (`cargo fmt`)
   - pass the existing tests (`cargo test`)
   - update `CHANGELOG.md` if the change is user visible (a newly supported
     assertion, a change in the report format, and so on)
3. Describe the why in the PR description, not just the what.

## Using generative AI

If a PR was written with help from a generative AI tool (Claude, Copilot,
ChatGPT, and so on), just say so in the description. It's not disqualifying,
but transparency is required, and the contributor remains responsible for
understanding and being able to explain the proposed code.

## Current project scope

The short term scope (see ROADMAP.md) is intentionally limited to a PHP
harness that replaces the `phpunit/phpunit` package, while keeping `php` as
the execution engine. PRs that expand toward a standalone PHP interpreter
(with no `php` at all) will be redirected to a separate issue discussion
before any code gets written. That's a change of a whole different scale,
not an incremental feature.
