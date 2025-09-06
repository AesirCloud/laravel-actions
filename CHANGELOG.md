# CHANGE LOG

All notable changes to `aesircloud/laravel-actions` will be documented in this file.

---

## 1.1.1 - 2025-09-06
- Simplify `Action::run` and `dispatch` to avoid passing arguments to the constructor.
- Improve default `asController` to throw when `handle` requires parameters.
- Inject `Filesystem` into `MakeActionCommand`.
- Align stub publish tag with README and update scaffold command references.

## 1.1.0 - 2025-08-26
- Drop Laravel 11 support and require PHP 8.4.

## 1.0.1 - 2025-02-28
- **Refactor**: Refactor the `Action` class to remove variadic abstract handle function.

## 1.0.0 - 2025-02-25
- Initial Release
