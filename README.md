# explayouts_content_browser_ui

Server-rendered content browser UI for Exponential Layouts on Exponential Legacy / Exponential 6. It provides a module view that browses, searches and selects content items using the `explayouts_content_browser_core` backend, rendered entirely with legacy templates — no JavaScript build step required.

Exponential Legacy port inspired by `netgen/content-browser-ui` (the original React package); this extension replaces it with a template/module based picker because the legacy stack does not run the Symfony/React toolchain.

## Key components

This extension ships no PHP classes of its own; it wires the sibling extensions into a browsable UI.

| Component | File | Purpose |
|-----------|------|---------|
| `explayouts_content_browser_ui/browser` module view | `modules/explayouts_content_browser_ui/browser.php` | Lists/searches children of a node, handles item selection and return redirects |
| Module definition | `modules/explayouts_content_browser_ui/module.php` | Declares the `browser` view with the `read` policy function and `LocationNodeID` parameter |
| Browser template | `design/standard/templates/explayouts_content_browser_ui/browser.tpl` | Renders the item list, search form and pagination |
| `settings/module.ini.append.php` | | Registers the module |
| `settings/design.ini.append.php` | | Registers the design extension |

## Quick start

```
/explayouts_content_browser_ui/browser/43
```

lists the first 25 children of node 43. Add `?Search=report` to filter, `?offset=25` to paginate.

## Documentation

- [INSTALL.md](INSTALL.md) — activation steps and dependencies
- [doc/USAGE.md](doc/USAGE.md) — URLs, parameters, template variables and customization
- [doc/FAQ.md](doc/FAQ.md) — frequently asked questions
- [doc/TODO.md](doc/TODO.md) — known gaps and planned work
- [doc/SUPPORT.md](doc/SUPPORT.md) — how to get help
