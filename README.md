# Pi Dates

A small WordPress plugin providing shortcodes for the current day, date, month, and year.

Drop `[pi_year]` into a footer for a copyright line that never goes stale, or `[pi_date_dm]` into a post for a full "Monday the 21st of May". Every shortcode reads the site's existing WordPress time zone and date settings, so output matches the rest of the site. There is nothing to configure.

**Requires:** WordPress 5.3+ · PHP 7.0+ · Licensed GPLv2 or later

---

## Shortcodes

### Years

| Shortcode | Output |
| --- | --- |
| `[pi_year]` | `2026` |
| `[pi_yr]` | `26` |

### Months

| Shortcode | Output |
| --- | --- |
| `[pi_month]` | `August` |
| `[pi_abr_month]` | `Aug` |
| `[pi_num_month]` | `8` |
| `[pi_znum_month]` | `08` |

### Days

| Shortcode | Output |
| --- | --- |
| `[pi_day]` | `Saturday` |
| `[pi_abr_day]` | `Sat` |
| `[pi_num_day]` | `1` |
| `[pi_znum_day]` | `01` |
| `[pi_ord_day]` | `1st` |
| `[pi_zord_day]` | `01st` |

### Combined

| Shortcode | Output |
| --- | --- |
| `[pi_date_d]` | `Saturday the 1st` |
| `[pi_date_m]` | `1st of August` |
| `[pi_date_dm]` | `Saturday the 1st of August` |

## Installation

### From a release

Download the latest zip from [Releases](https://github.com/swmpi/PiDates/releases) and install it through **Plugins → Add New → Upload Plugin**.

### From source

```bash
cd wp-content/plugins
git clone https://github.com/swmpi/PiDates.git pi-dates
```

The directory must be named `pi-dates`. Nothing is compiled and there are no dependencies, so the clone is ready to activate as-is.

## Usage

In post or page content, use the shortcode directly:

```
Copyright [pi_year] — updated [pi_date_dm]
```

In a theme template, wrap it in `do_shortcode()`:

```php
<?php echo do_shortcode('[pi_year]'); ?>
```

The settings screen at **Settings → Pi Dates** is read only. It shows which WordPress time zone, date format, and time format are in use, and lists every shortcode with live example output.

## Notes

### Caching

Shortcodes are evaluated when a page is generated. Under a full page cache, `[pi_day]` shows whichever day the page was cached on until the cache clears. Exclude the page or shorten its cache lifetime if that matters.

### Translations

The plugin is fully internationalised, but **it does not load its own translation files**. Translations are supplied by the WordPress.org translation community and downloaded by WordPress automatically.

That means an install from this repository, or from a zip built from it, runs in English regardless of what is in `languages/`. Only installs from the WordPress.org plugin directory receive translations.

Day and month names are the exception and always follow the site language, because the plugin uses `wp_date()` rather than PHP's `date()`.

If you are packaging this plugin for distribution outside WordPress.org, you will need to add a `load_plugin_textdomain()` call on `init`. There is a comment at the foot of `pi-dates.php` marking where it was removed and why.

## Development

### Layout

```
pi-dates.php              Plugin header, bootstrap, constants, activation, admin assets
pi-dates-settings.php     Settings registration and the settings screen
pi-dates-shortcodes.php   Shortcode callbacks and the date formatting helper
uninstall.php             Data removal on plugin deletion, multisite aware
css/pi-dates-admin.css    Settings screen styles, enqueued on that screen only
languages/pi-dates.pot    Translation template
readme.txt                WordPress.org readme, and the canonical changelog
```

All PHP is under the `PiDates` namespace, including `uninstall.php`.

### Date format strings

Format strings passed to `wp_date()` **must use single quotes**. In a double quoted PHP string, `\t`, `\e`, and `\f` are interpreted as control characters before `wp_date()` ever sees them, which is what produced the garbled output in versions before 0.2.2.

```php
wp_date('l \t\h\e jS');   // correct  -> Saturday the 1st
wp_date("l \t\h\e jS");   // wrong    -> Saturday <tab>h<esc> 1st
```

The combined formats are wrapped in `_x()` with a `date format` context so translators can reorder the parts. Each carries a translator comment explaining that literal letters need backslash escaping.

### Regenerating the translation template

```bash
wp i18n make-pot . languages/pi-dates.pot
```

Regenerate whenever a user facing string is added or reworded. A stale template is worse than none, because translators work from it.

### Checks before a release

```bash
# syntax
find . -name '*.php' -exec php -l {} \;

# WordPress Plugin Check (as used by the plugin directory)
wp plugin check pi-dates
```

A few Plugin Check findings are suppressed inline with `phpcs:ignore` and a justification. Each names the specific sniff rather than blanket ignoring the line, so unrelated problems still get reported.

Bump the version in three places when releasing: the `Version:` header, `const VERSION` in `pi-dates.php`, and `Stable tag` in `readme.txt`. `PI_DATES_VERSION` derives from the constant and follows automatically.

## Changelog

See the `== Changelog ==` section of [readme.txt](readme.txt), which is the canonical record.

## License

GPLv2 or later. See [https://www.gnu.org/licenses/gpl-2.0.html](https://www.gnu.org/licenses/gpl-2.0.html).
