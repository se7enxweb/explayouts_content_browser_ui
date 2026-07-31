# TODO — explayouts_content_browser_ui

- The allowed content class list (currently empty = all classes) and the page size (25) are hard-coded in `modules/explayouts_content_browser_ui/browser.php`; move both to an INI file (for example `contentbrowser.ini`) so deployments can configure them.
- `browser.php` force-disables debug output via `eZDebug::updateSettings( array( 'debug-enabled' => false ) )` at the top of the view; this should be conditional, not hard-coded.
- Only one template (`browser.tpl`) in the standard design; no admin design variant is provided.
- No JSON endpoint — selection only works through the full-page redirect flow, which makes embedding in modal/AJAX pickers awkward.
- The `folder`-as-location constructor argument is passed to `expLayoutsContentBrowserCoreBackend`, but the backend does not implement `locationContentTypes` yet, so it currently has no effect.
