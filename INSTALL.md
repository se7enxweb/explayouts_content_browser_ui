# Installing explayouts_content_browser_ui

## Requirements

- Exponential Legacy / Exponential 6
- PHP 8.1, 8.2, 8.3 or 8.4

## Dependencies

- `explayouts_content_browser` — provides `expLayoutsContentBrowserItem`
- `explayouts_content_browser_core` — provides `expLayoutsContentBrowserCoreBackend`, which the `browser` view uses

Activate both before this extension.

## Steps

1. Place the extension in `extension/explayouts_content_browser_ui`.

2. Activate it (after its dependencies) in `settings/override/site.ini.append.php`:

   ```ini
   [ExtensionSettings]
   ActiveExtensions[]=explayouts_content_browser
   ActiveExtensions[]=explayouts_content_browser_core
   ActiveExtensions[]=explayouts_content_browser_ui
   ```

   To activate it for a single siteaccess only, use `ActiveAccessExtensions[]` in `settings/siteaccess/<name>/site.ini.append.php` instead.

3. Regenerate autoloads:

   ```bash
   php bin/php/ezpgenerateautoloads.php -e
   ```

4. Clear caches:

   ```bash
   php bin/php/ezcache.php --clear-all --purge --allow-root-user
   ```

5. Grant access: the `browser` view uses the `read` policy function of the `explayouts_content_browser_ui` module. Add a role policy for module `explayouts_content_browser_ui`, function `read` (or `*`) to the roles that should use the picker.
