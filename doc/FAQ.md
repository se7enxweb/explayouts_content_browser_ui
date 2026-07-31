# FAQ — explayouts_content_browser_ui

## Does this include the React content browser assets?

No. It is a deliberately lightweight, server-rendered replacement. The legacy stack does not run the Symfony/React toolchain, so the picker is a normal module view plus a template.

## Can I restrict which content classes are shown?

Not via configuration yet. `modules/explayouts_content_browser_ui/browser.php` builds its backend with an empty allowed-class list, so every class is listed. To restrict classes today you must pass identifiers in that constructor call; an INI setting is on the TODO list.

## How do I embed the picker into my own edit form?

Link to `/explayouts_content_browser_ui/browser/<node>?return_uri=<your-form-uri>`. After the editor selects an item, the module redirects back to `return_uri` with `selected_node_id`, `selected_object_id` and `selected_name` appended as query parameters.

## Why do I get an access denied error?

The `browser` view is guarded by the module's `read` policy function. Grant a role policy for module `explayouts_content_browser_ui`, function `read`.

## Can I change the page size?

Not without editing code — the limit is fixed at 25 in `browser.php`.
