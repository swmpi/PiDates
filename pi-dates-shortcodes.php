<?php
/**
 * Pi Dates Shortcodes
 *
 * This file contains all the shortcode functions for the Pi Dates plugin.
 */
namespace PiDates;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/*
Writes to the PHP error log only when WordPress debug logging is switched on.
WP_DEBUG_LOG may hold either true or a custom log path, so both count as
enabled. Without this gate a misconfigured site writes an entry on every page
load, since these shortcodes can run several times per request.

Log messages are deliberately left untranslated. They are read by developers in
a log file, not shown to visitors, and translating them would make searching for
a known message unreliable.
*/
function pi_dates_log($message) {
    if (!defined('WP_DEBUG') || !WP_DEBUG) {
        return;
    }
    if (!defined('WP_DEBUG_LOG') || !WP_DEBUG_LOG) {
        return;
    }
    /*
    Part of the message can come from a translated format string, so line breaks
    are flattened to stop a crafted translation forging extra log entries.
    */
    $message = str_replace(array("\r", "\n"), ' ', (string) $message);
    // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log -- Deliberate diagnostic logging, unreachable unless both WP_DEBUG and WP_DEBUG_LOG are enabled, as checked above.
    error_log('Pi Dates plugin error: ' . $message);
}

// This function logs the error message and returns an escaped fallback string.
function pi_dates_error_handler($e, $fallback) {
    pi_dates_log($e->getMessage());
    return esc_html($fallback);
}

/*
Runs the format string through wp_date() and returns escaped output.
wp_date() returns false on failure rather than throwing, so both that and a
thrown exception are handled here. Format strings must use single quotes so
that backslash escapes reach wp_date() intact.

Format strings are never translated. They are date() format characters, and
wp_date() already returns localised month and day names based on the site
locale. The fallback strings are passed in already translated by the caller,
which is safe because shortcodes only run once init has fired.

Every exit from this function is escaped, including the fallbacks. Those come
from a translation file, which is third party content that reaches the front end
of the site, so it is not treated as trusted.
*/
function pi_dates_format($format, $fallback) {
    try {
        $date = wp_date($format);
        if (false === $date) {
            pi_dates_log('wp_date() failed for the format ' . $format);
            return esc_html($fallback);
        }
        return esc_html($date);
    } catch (\Exception $e) {
        return pi_dates_error_handler($e, $fallback);
    }
}

/* 
Shortcodes are added first by creating the PHP function performing a given task.
This is followed by adding the shortcode with the name and function name.
Starting with current dates, with single values, in various formats.
*/
// Years

// Returns the current year in format YYYY
function get_year(){
    return pi_dates_format('Y', __('Year unavailable', 'pi-dates'));
}
add_shortcode('pi_year', 'PiDates\\get_year');

// Returns the current year in format YY
function get_yr(){
    return pi_dates_format('y', __('Year unavailable', 'pi-dates'));
}
add_shortcode('pi_yr', 'PiDates\\get_yr');

// Months

// Returns the current month's date in format 01 to 12
function get_znum_month(){
    return pi_dates_format('m', __('Month unavailable', 'pi-dates'));
}
add_shortcode('pi_znum_month', 'PiDates\\get_znum_month');

// Returns the current month's date in format 1 to 12
function get_num_month(){
    return pi_dates_format('n', __('Month unavailable', 'pi-dates'));
}
add_shortcode('pi_num_month', 'PiDates\\get_num_month');

// Returns the current month's abbreviate name in format Jan
function get_abr_month(){
    return pi_dates_format('M', __('Month unavailable', 'pi-dates'));
}
add_shortcode('pi_abr_month', 'PiDates\\get_abr_month');

// Returns the current month in format January
function get_month(){
    return pi_dates_format('F', __('Month unavailable', 'pi-dates'));
}
add_shortcode('pi_month', 'PiDates\\get_month');

// Days

// Returns the current day's date in format 01 to 31
function get_znum_day(){
    return pi_dates_format('d', __('Day unavailable', 'pi-dates'));
}
add_shortcode('pi_znum_day', 'PiDates\\get_znum_day');

// Returns the current day's date in format 1 to 31
function get_num_day(){
    return pi_dates_format('j', __('Day unavailable', 'pi-dates'));
}
add_shortcode('pi_num_day', 'PiDates\\get_num_day');

// Returns the current day's date with ordinal in format 01st, 02nd, 03rd, 04th
function get_zord_day(){
    return pi_dates_format('dS', __('Day unavailable', 'pi-dates'));
}
add_shortcode('pi_zord_day', 'PiDates\\get_zord_day');

// Returns the current day's date with ordinal in format 1st, 2nd, 3rd, 4th
function get_ord_day(){
    return pi_dates_format('jS', __('Day unavailable', 'pi-dates'));
}
add_shortcode('pi_ord_day', 'PiDates\\get_ord_day');

// Returns the current day's abbreviate name in format Mon
function get_abr_day(){
    return pi_dates_format('D', __('Day unavailable', 'pi-dates'));
}
add_shortcode('pi_abr_day', 'PiDates\\get_abr_day');

// Returns the current year in format Monday
function get_name_day(){
    return pi_dates_format('l', __('Day unavailable', 'pi-dates'));
}
add_shortcode('pi_day', 'PiDates\\get_name_day');

// Pre-formated text strings with various values and combinations of values

/*
The connecting words in these formats are escaped date() literals, so they are
translated through the format string itself rather than concatenated. A
translator can reorder the parts freely, for example putting the month first,
and must keep the backslash before each letter of a connecting word.
*/

// Return day and date in the format Monday the 21st 
function get_date_d(){
    return pi_dates_format(
        /* translators: date() format string. Escape any literal letters with a backslash, as in \t\h\e for the word "the". l = weekday name, jS = day of month with ordinal suffix. */
        _x('l \t\h\e jS', 'date format', 'pi-dates'),
        __('Date unavailable', 'pi-dates')
    );
}
add_shortcode('pi_date_d', 'PiDates\\get_date_d');

// Return month and date in the format 21st of May
function get_date_m(){
    return pi_dates_format(
        /* translators: date() format string. Escape any literal letters with a backslash, as in \o\f for the word "of". jS = day of month with ordinal suffix, F = month name. */
        _x('jS \o\f F', 'date format', 'pi-dates'),
        __('Date unavailable', 'pi-dates')
    );
}
add_shortcode('pi_date_m', 'PiDates\\get_date_m');

// Return day, month and date in the format Monday the 21st of May
function get_date_dm(){
    return pi_dates_format(
        /* translators: date() format string. Escape any literal letters with a backslash, as in \t\h\e and \o\f. l = weekday name, jS = day of month with ordinal suffix, F = month name. */
        _x('l \t\h\e jS \o\f F', 'date format', 'pi-dates'),
        __('Date unavailable', 'pi-dates')
    );
}
add_shortcode('pi_date_dm', 'PiDates\\get_date_dm');
