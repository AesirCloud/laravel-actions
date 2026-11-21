# CHANGE LOG

All notable changes to `aesircloud/laravel-actions` will be documented in this file.

---

## 1.3.0 - 2026-11-21

- Expand runtime support to PHP 8.3 while retaining existing supported runtimes and framework APIs.
- Verify fresh and locked installations, platform/security checks and actual tests across PHP 8.3–8.6.

## 1.2.1 - 2026-11-20

- Add required PHP 8.6 coverage and compatible Pest branches without changing the runtime minimum.

## 1.2.0 - 2026-11-05
- Require Laravel 13 and PHP 8.5; older Laravel and PHP versions are no longer supported.
- Upgrade the test suite to Pest 4 and Orchestra Testbench 11.
- Run CI tests on PHP 8.5.

## 1.1.1 - 2025-09-06
- Simplify `Action::run` and `dispatch` to avoid passing arguments to the constructor.
- Improve default `asController` to throw when `handle` requires parameters.
- Inject `Filesystem` into `MakeActionCommand`.
- Align stub publish tag with README and update scaffold command references.
- Queueing an action without parameters now dispatches the action instance instead of a closure.

## 1.1.0 - 2025-08-26
- Drop Laravel 11 support and require PHP 8.4.

## 1.0.1 - 2025-02-28
- **Refactor**: Refactor the `Action` class to remove variadic abstract handle function.

## 1.0.0 - 2025-02-25
- Initial Release
