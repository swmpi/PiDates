=== Pi Dates ===
Contributors: sm314
Tags: date, time, shortcode, day, year
Requires at least: 5.3
Tested up to: 7.0
Stable tag: 0.3.0
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Shortcodes for showing the current day, date, month, and year anywhere on your site, using your own WordPress date and time settings.

== Description ==

Pi Dates adds a set of shortcodes that print the current date in a range of formats. Drop `[pi_year]` into a footer for a copyright line that never goes stale, or `[pi_date_dm]` into a post for a full "Monday the 21st of May".

Every shortcode reads your existing WordPress time zone, date, and time settings, so output matches the rest of your site. There is nothing to configure. The settings screen at **Settings → Pi Dates** shows which settings are in use and lists every shortcode with live example output.

= Years =

* `[pi_year]` — four digit year, for example 2026
* `[pi_yr]` — two digit year, for example 26

= Months =

* `[pi_month]` — full month name, for example August
* `[pi_abr_month]` — abbreviated month name, for example Aug
* `[pi_num_month]` — month number, 1 to 12
* `[pi_znum_month]` — month number with a leading zero, 01 to 12

= Days =

* `[pi_day]` — full day name, for example Saturday
* `[pi_abr_day]` — abbreviated day name, for example Sat
* `[pi_num_day]` — day of the month, 1 to 31
* `[pi_znum_day]` — day of the month with a leading zero, 01 to 31
* `[pi_ord_day]` — day of the month with an ordinal, for example 1st
* `[pi_zord_day]` — day of the month with a leading zero and an ordinal, for example 01st

= Combined formats =

* `[pi_date_d]` — day and date, for example Saturday the 1st
* `[pi_date_m]` — date and month, for example 1st of August
* `[pi_date_dm]` — day, date and month, for example Saturday the 1st of August

= Translations =

Pi Dates is fully translatable. Translations are supplied by the WordPress.org
translation community and are downloaded by WordPress automatically as they
become available, so no language files ship inside the plugin itself.

Day and month names always follow your site language, because the plugin uses
the WordPress date functions rather than the raw PHP ones. The connecting words
in the combined formats above, such as "the" and "of", are translated separately
and can be reordered by translators to suit the language.

If you would like to help translate Pi Dates, visit the WordPress.org
[translation page for this plugin](https://translate.wordpress.org/projects/wp-plugins/pi-dates/).

== Installation ==

1. Upload the `pi-dates` folder to `/wp-content/plugins/`, or install the plugin through the **Plugins → Add New** screen.
1. Activate the plugin through the **Plugins** screen.
1. Add any of the shortcodes listed above to a post, page, widget, or block.

There is no configuration step. Visit **Settings → Pi Dates** to see the current settings and a reference table of every shortcode.

== Frequently Asked Questions ==

= Do I need to set anything up? =

No. Pi Dates uses the time zone, date format, and time format already set under **Settings → General**. Changing those settings changes what the shortcodes display.

= Why is the date wrong, or stuck on an older date? =

The most common cause is page caching. Shortcodes are worked out when the page is generated, so if a caching plugin or a host level cache stores that page, the date is frozen at the moment the page was cached until the cache clears.

If you use these shortcodes on a page that is cached for a long time, either exclude that page from caching or shorten the cache lifetime.

= Can I use these shortcodes in a theme template? =

Yes. Use `do_shortcode()`, for example `<?php echo do_shortcode('[pi_year]'); ?>`.

= Can I change the wording in [pi_date_d] and the other combined formats? =

Not from the settings screen. The wording comes from the translation files, so it follows your site language. If you need a format that is not listed, the individual shortcodes can be combined, for example `[pi_day] [pi_ord_day]`.

= Does the plugin store any data? =

Only a single option recording the installed version. It is left in place when the plugin is deactivated, so that deactivating to troubleshoot does not discard anything, and it is removed completely when the plugin is deleted.

= Does it work on multisite? =

Yes. On deletion the plugin cleans up after itself on every site in the network.

== Changelog ==

= 0.3.0 =
* Fixed the combined date shortcodes, which printed stray characters instead of the words "the" and "of".
* Fixed the settings screen, where the time zone, date format, and time format rows did not appear.
* Fixed activation, which did not record the installed version.
* Added an uninstall routine that removes plugin data when the plugin is deleted, including on multisite. Data is now kept on deactivation.
* Added full translation support.
* Added an accessible label to each field on the settings screen.
* Added a stylesheet for the settings screen, loaded only on that screen.
* Error logging now happens only when WordPress debugging is enabled.
* Various security and code quality improvements.

= 0.2.2 =
* Corrections to the combined date format shortcodes.

= 0.1.2 =
* Initial release.

== Upgrade Notice ==

= 0.3.0 =
Fixes the combined date shortcodes, which displayed stray characters, and the settings screen, which was missing its fields. Adds translation support and proper cleanup on deletion. Recommended for all users.
