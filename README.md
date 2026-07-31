Exponential Layouts Content Browser UI
======================================

General description
-------------------

Exponential Layouts Content Browser UI (`explayouts_content_browser_ui`) is
the server-rendered content browser UI for Exponential Layouts on
Exponential Legacy / Exponential 6. It provides a module view that browses,
searches and selects content items using the
`explayouts_content_browser_core` backend, rendered entirely with legacy
templates — no JavaScript build step required.

This extension is an Exponential Legacy port inspired by
`netgen/content-browser-ui` (the original React package); it replaces the
React application with a template/module based picker because the legacy
stack does not run the Symfony/React toolchain. It provides the following
capabilities:

- Content browsing UI - Use this feature to browse and drill down through
  the content tree from a simple, server-rendered module view.
- Subtree search - Use this feature to search a subtree with multi-term
  matching, straight from the browser's search form.
- Item picking - Use this feature to let editors select a content item and
  return it to any form or view via a redirect with the selection appended.
- Pagination - Use this feature to page through large listings with
  ready-made next/previous pagination helpers.

Features
--------

The following features are provided by the Exponential Layouts Content
Browser UI extension:

- A complete, self-contained browser module view:
  `/explayouts_content_browser_ui/browser/<LocationNodeID>` lists the first
  25 children of the given node (defaulting to node 2 when omitted). All
  content classes are shown; the view constructs its backend as
  `new expLayoutsContentBrowserCoreBackend( array(), array( 'folder' ) )`.
- Search built in: the `Search` query parameter runs
  `expLayoutsContentBrowserCoreBackend::searchItems()` over the subtree
  (depth 10, up to 1000 nodes); every whitespace-separated term must match
  the node name, class identifier or class name.
- Pagination built in: the `offset` query parameter combines with the fixed
  page size of 25, and the template receives ready-made `has_next` /
  `next_offset` / `has_previous` / `previous_offset` values.
- A full picker workflow for other forms: open the browser with a
  `return_uri`, and when the editor selects an item
  (`action=select&selected_node_id=<id>`) the view loads it through
  `expLayoutsContentBrowserCoreBackend::loadItem()` and redirects back to
  `return_uri` with `selected_node_id`, `selected_object_id` and
  `selected_name` appended as query parameters.
- Inline selection without a redirect: when no `return_uri` is given, the
  template is rendered with `has_selection` set and `selected_item` holding
  the picked item's `toArray()` hash, so a confirmation can be rendered in
  place.
- Access control through the standard role/policy system: the `browser`
  view is guarded by the module's `read` policy function.
- A single, easily overridable template
  (`design/standard/templates/explayouts_content_browser_ui/browser.tpl`)
  that renders the item list, search form and pagination, and receives a
  documented set of variables (`items`, `total`, `location_node_id`,
  `search`, `offset`, `limit`, pagination helpers, `return_uri`,
  `has_selection`, `selected_item`).
- No JavaScript build step, no Node toolchain, no asset pipeline — plain
  module + template code that works on any Exponential Legacy install.

Version
-------

- The current version of Exponential Layouts Content Browser UI is 1.0.0
- Last Major update: July 30, 2026

Copyright
---------

- Exponential Layouts Content Browser UI is copyright 1998 - 2026 7x
- See: [LICENSE.md](LICENSE.md) for more information on the terms of the
  copyright and license

License
-------

Exponential Layouts Content Browser UI is licensed under the GNU General
Public License.

The complete license agreement is included in the [LICENSE.md](LICENSE.md)
file.

Exponential Layouts Content Browser UI is free software: you can
redistribute it and/or modify it under the terms of the GNU General Public
License as published by the Free Software Foundation, either version 2 of
the License or at your option a later version.

Exponential Layouts Content Browser UI is distributed in the hope that it
will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty
of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General
Public License for more details.

The GNU GPL gives you the right to use, modify and redistribute
Exponential Layouts Content Browser UI under certain conditions. The GNU
GPL license is distributed with the software, see the file
[LICENSE.md](LICENSE.md).

It is also available at http://www.gnu.org/licenses/gpl.txt

You should have received a copy of the GNU General Public License along with
Exponential Layouts Content Browser UI in [LICENSE.md](LICENSE.md). If not,
see http://www.gnu.org/licenses/.

Using Exponential Layouts Content Browser UI under the terms of the GNU GPL
is free (as in freedom).

For more information or questions please contact info@se7enx.com

Requirements
------------

The following requirements exist for using the Exponential Layouts Content
Browser UI extension:

Exponential version
- Make sure you use Exponential 6 / Exponential Legacy.

PHP version
- Make sure you have PHP 8.1, 8.2, 8.3 or 8.4.

Extension dependencies
- `explayouts_content_browser` — provides `expLayoutsContentBrowserItem`.
- `explayouts_content_browser_core` — provides
  `expLayoutsContentBrowserCoreBackend`, which the `browser` view uses.
- Activate both before this extension.

Installation
------------

Installation is the standard extension procedure: place the extension in
`extension/explayouts_content_browser_ui`, activate it after its
dependencies via `ActiveExtensions[]` (or `ActiveAccessExtensions[]` for a
single siteaccess), regenerate autoloads, clear caches, and grant a role
policy for module `explayouts_content_browser_ui`, function `read` (or `*`)
to the roles that should use the picker.

See [INSTALL.md](INSTALL.md) for the complete step-by-step installation
instructions.

Usage
-----

This extension ships no PHP classes of its own; it wires the sibling
extensions into a browsable UI:

| Component | File | Purpose |
|-----------|------|---------|
| `explayouts_content_browser_ui/browser` module view | `modules/explayouts_content_browser_ui/browser.php` | Lists/searches children of a node, handles item selection and return redirects |
| Module definition | `modules/explayouts_content_browser_ui/module.php` | Declares the `browser` view with the `read` policy function and `LocationNodeID` parameter |
| Browser template | `design/standard/templates/explayouts_content_browser_ui/browser.tpl` | Renders the item list, search form and pagination |
| `settings/module.ini.append.php` | | Registers the module (`ExtensionRepositories[]`, `ModuleList[]`) |
| `settings/design.ini.append.php` | | Registers the design extension (`DesignExtensions[]`) |

Quick start:

```
/explayouts_content_browser_ui/browser/43
```

lists the first 25 children of node 43. The supported query parameters are:

| Parameter | Purpose |
|-----------|---------|
| `Search` | Multi-term substring search within the subtree (via `searchItems()`) |
| `offset` | Pagination offset; the page size is fixed at 25 |
| `action=select` | Select an item instead of browsing |
| `selected_node_id` | Node ID to select when `action=select` |
| `return_uri` | URI to redirect back to after a selection |

Examples:

```
/explayouts_content_browser_ui/browser/43?Search=report
/explayouts_content_browser_ui/browser/43?offset=25
/explayouts_content_browser_ui/browser/43?action=select&selected_node_id=123&return_uri=/my/form
```

To use the browser as a picker for another form, open it with a
`return_uri` pointing back to your form; after the editor picks an item the
view redirects to `return_uri` with `selected_node_id`,
`selected_object_id` and `selected_name` appended, which your receiving
view reads from `eZHTTPTool`.

The full usage guide in [doc/USAGE.md](doc/USAGE.md) covers the URLs and
parameters in depth, the template variables, the picker and inline
selection scenarios, and the three customization layers of this stack: the
settings layer (the two INI appends and the configuration cascade), the
template layer (overriding `browser.tpl` through the design cascade in your
own design extension) and the PHP layer (subclassing the backend classes in
the sibling extensions instead of editing the view).

Documentation
-------------

| Document | Description |
|----------|-------------|
| [INSTALL.md](INSTALL.md) | Requirements, dependencies, activation steps and role policy setup |
| [doc/USAGE.md](doc/USAGE.md) | URLs, parameters, template variables, scenarios and the settings/template/PHP customization layers |
| [doc/FAQ.md](doc/FAQ.md) | Frequently asked questions and answers |
| [doc/TODO.md](doc/TODO.md) | Known gaps and planned improvements |
| [doc/SUPPORT.md](doc/SUPPORT.md) | Where and how to get help |
| [LICENSE.md](LICENSE.md) | The complete GNU General Public License agreement |

Troubleshooting
---------------

Read the FAQ
- Some problems are more common than others. The most common ones are listed
  in [doc/FAQ.md](doc/FAQ.md).

Use our support systems
- If you find a bug or defect, please report it to the
  [Exponential Layouts Content Browser UI: Issue Tracker](https://github.com/se7enxweb/explayouts_content_browser_ui/issues)
- For commercial support and custom development please visit
  [se7enx.com](https://se7enx.com)
