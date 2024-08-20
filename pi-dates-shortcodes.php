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

// This function logs the error message and returns a fallback string.
function pi_dates_error_handler($e, $fallback) {
    error_log('Pi Dates plugin error: ' . $e->getMessage());
    return $fallback;
}

/* 
Shortcodes are added first by creating the PHP function performing a given task.
This is followed by adding the shortcode with the name and function name.
Starting with current dates, with single values, in various formats.
*/
// Years

// Returns the current year in format YYYY
function get_year(){
    try {
        return esc_html(wp_date("Y"));
    } catch (Exception $e) {
        return pi_dates_error_handler($e, 'Year unavailable');
    }
}
add_shortcode('pi_year', 'PiDates\\get_year');

// Returns the current year in format YY
function get_yr(){
    try {
        return esc_html(wp_date("y"));
    } catch (Exception $e) {
        return pi_dates_error_handler($e, 'Year unavailable');
    }
}
add_shortcode('pi_yr', 'PiDates\\get_yr');

// Months

// Returns the current month's date in format 01 to 12
function get_znum_month(){
    try {
        return esc_html(wp_date("m"));
    } catch (Exception $e) {
        return pi_dates_error_handler($e, 'Month unavailable');
    }
}
add_shortcode('pi_znum_month', 'PiDates\\get_znum_month');

// Returns the current month's date in format 1 to 12
function get_num_month(){
    try {
        return esc_html(wp_date("n"));
    } catch (Exception $e) {
        return pi_dates_error_handler($e, 'Month unavailable');
    }
}
add_shortcode('pi_num_month', 'PiDates\\get_num_month');

// Returns the current month's abbreviate name in format Jan
function get_abr_month(){
    try {
        return esc_html(wp_date("M"));
    } catch (Exception $e) {
        return pi_dates_error_handler($e, 'Month unavailable');
    }
}
add_shortcode('pi_abr_month', 'PiDates\\get_abr_month');

// Returns the current month in format January
function get_month(){
    try {
        return esc_html(wp_date("F"));
    } catch (Exception $e) {
        return pi_dates_error_handler($e, 'Month unavailable');
    }
}
add_shortcode('pi_month', 'PiDates\\get_month');

// Days

// Returns the current day's date in format 01 to 31
function get_znum_day(){
    try {
        return esc_html(wp_date("d"));
    } catch (Exception $e) {
        return pi_dates_error_handler($e, 'Day unavailable');
    }
}
add_shortcode('pi_znum_day', 'PiDates\\get_znum_day');

// Returns the current day's date in format 1 to 31
function get_num_day(){
    try {
        return esc_html(wp_date("j"));
    } catch (Exception $e) {
        return pi_dates_error_handler($e, 'Day unavailable');
    }
}
add_shortcode('pi_num_day', 'PiDates\\get_num_day');

// Returns the current day's date with ordinal in format 01st, 02nd, 03rd, 04th
function get_zord_day(){
    try {
        return esc_html(wp_date("dS"));
    } catch (Exception $e) {
        return pi_dates_error_handler($e, 'Day unavailable');
    }
}
add_shortcode('pi_zord_day', 'PiDates\\get_zord_day');

// Returns the current day's date with ordinal in format 1st, 2nd, 3rd, 4th
function get_ord_day(){
    try {
        return esc_html(wp_date("jS"));
    } catch (Exception $e) {
        return pi_dates_error_handler($e, 'Day unavailable');
    }
}
add_shortcode('pi_ord_day', 'PiDates\\get_ord_day');

// Returns the current day's abbreviate name in format Mon
function get_abr_day(){
    try {
        return esc_html(wp_date("D"));
    } catch (Exception $e) {
        return pi_dates_error_handler($e, 'Day unavailable');
    }
}
add_shortcode('pi_abr_day', 'PiDates\\get_abr_day');

// Returns the current year in format Monday
function get_name_day(){
    try {
        return esc_html(wp_date("l"));
    } catch (Exception $e) {
        return pi_dates_error_handler($e, 'Day unavailable');
    }
}
add_shortcode('pi_day', 'PiDates\\get_name_day');

// Pre-formated text strings with various values and combinations of values

// Return day and date in the format Monday the 21st 
function get_date_d(){
    try {
        return esc_html(wp_date("l \t\h\e jS"));
    } catch (Exception $e) {
        return pi_dates_error_handler($e, 'Date unavailable');
    }
}
add_shortcode('pi_date_d', 'PiDates\\get_date_d');

// Return month and date in the format 21st of May
function get_date_m(){
    try {
        return esc_html(wp_date("jS \o\f F"));
    } catch (Exception $e) {
        return pi_dates_error_handler($e, 'Date unavailable');
    }
}
add_shortcode('pi_date_m', 'PiDates\\get_date_m');

// Return day, month and date in the format Monday the 21st of May
function get_date_dm(){
    try {
        return esc_html(wp_date("l \t\h\e jS \o\f F"));
    } catch (Exception $e) {
        return pi_dates_error_handler($e, 'Date unavailable');
    }
}
add_shortcode('pi_date_dm', 'PiDates\\get_date_dm');