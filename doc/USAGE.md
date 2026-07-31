# Using explayouts_content_browser_ui

## Browsing

```
/explayouts_content_browser_ui/browser/<LocationNodeID>
```

Lists the first 25 children of the given node (defaults to node 2 when omitted). All content classes are shown; the view constructs its backend as:

```php
$backend = new expLayoutsContentBrowserCoreBackend( array(), array( 'folder' ) );
```

## Query parameters

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

## Scenario: browsing and searching

- Drill down by linking each container row back to `/explayouts_content_browser_ui/browser/{$item.node_id}`.
- Search runs `expLayoutsContentBrowserCoreBackend::searchItems()` over the subtree (depth 10, up to 1000 nodes); every whitespace-separated term must match name, class identifier or class name.
- Pagination combines `offset` with the fixed limit of 25; the template receives ready-made `has_next` / `next_offset` / `has_previous` / `previous_offset` values.

## Scenario: using it as a picker for another form

Open the browser with a `return_uri` pointing back to your form. When the editor picks an item (`action=select&selected_node_id=<id>`), the view loads the item through `expLayoutsContentBrowserCoreBackend::loadItem()` and redirects to `return_uri` with these parameters appended:

- `selected_node_id`
- `selected_object_id`
- `selected_name`

Your receiving view reads them from `eZHTTPTool` and stores whatever it needs.

## Scenario: inline selection without a redirect

Without a `return_uri`, the selection does not redirect; the template is rendered with `has_selection` set and `selected_item` holding the picked item's `toArray()` hash, so the confirmation can be rendered in place.

## Template variables

`design/standard/templates/explayouts_content_browser_ui/browser.tpl` receives:

| Variable | Content |
|----------|---------|
| `items` | Array of `toArray()` hashes (`node_id`, `object_id`, `name`, `class_identifier`, `is_container`, ...) |
| `total` | Total item count for the current listing/search |
| `location_node_id` | Current parent node |
| `search` | Current search string |
| `offset`, `limit` | Current page window |
| `next_offset`, `has_next`, `previous_offset`, `has_previous` | Pagination helpers |
| `return_uri` | Pass-through of the picker return URI |
| `has_selection`, `selected_item` | Selection state when no redirect happened |

Example listing markup:

```
<table class="list">
    {foreach $items as $item}
        <tr>
            <td>{$item.name|wash}</td>
            <td>{$item.class_identifier|wash}</td>
        </tr>
    {/foreach}
</table>
```

## Customization

### Settings layer (INI)

This extension ships two INI appends:

- `settings/design.ini.append.php` — adds `explayouts_content_browser_ui` to `[ExtensionSettings] DesignExtensions[]` so its `design/standard` templates are found.
- `settings/module.ini.append.php` — adds the module to `[ModuleSettings] ExtensionRepositories[]` and `ModuleList[]`.

The module view itself reads no INI values yet (allowed classes and page size are hard-coded — see [TODO.md](TODO.md)). Any INI it registers follows this stack's cascade, lowest to highest priority:

1. `settings/*.ini` — kernel defaults
2. `extension/<ext>/settings/*.ini.append.php` — extension defaults (the two files above)
3. `settings/siteaccess/<siteaccess>/*.ini.append.php` — siteaccess overrides
4. `extension/<ext>/settings/siteaccess/<siteaccess>/*.ini.append.php` — extension siteaccess overrides
5. `settings/override/*.ini.append.php` — global overrides (always win)

For example, a siteaccess that should not expose the picker can be excluded by activating the extension only where needed via `ActiveAccessExtensions[]` instead of `ActiveExtensions[]`.

### Template layer (design overrides)

The view fetches `design:explayouts_content_browser_ui/browser.tpl`, resolved through the standard design cascade: the siteaccess design first, then each `AdditionalSiteDesignList[]` design, then `standard`. To restyle the browser without touching this extension, ship

```
design/<your_design>/templates/explayouts_content_browser_ui/browser.tpl
```

in your own design extension (registered via `design.ini` `DesignExtensions[]`) and make sure `<your_design>` comes before `standard` for the siteaccess (`site.ini` `[DesignSettings]`). Design extensions listed earlier in `DesignExtensions[]` win when several provide the same design/template path.

### PHP layer (extension points)

This extension deliberately contains no classes; all reusable logic lives in `expLayoutsContentBrowserCoreBackend` (`explayouts_content_browser_core`) and `expLayoutsContentBrowserItem` (`explayouts_content_browser`). Customize behaviour by subclassing those (both expose `protected` hook methods) rather than editing `modules/explayouts_content_browser_ui/browser.php`. If you need a differently-wired view (other allowed classes, another page size, JSON output), add your own module view that reuses the same backend — the view script is intentionally small enough to serve as a reference implementation.
