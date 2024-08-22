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

function pi_dates_register_settings() {
    register_setting(
        'pi_dates_options',
        'pi_dates_options',
        'PiDates\\pi_dates_sanitize_options'
    );

    add_settings_section(
        'pi_dates_main_section',
        'Pi Dates Settings',
        'PiDates\\pi_dates_main_section_cb',
        'pi-dates-settings'
    );

    add_settings_field(
        'pi_dates_wp_timezone',
        'Time Zone',
        'PiDates\\pi_dates_wp_timezone_cb',
        'pi-dates-settings',
        'pi_dates_main_section'
    );

    add_settings_field(
        'pi_dates_wp_date_format',
        'Date Format',
        'PiDates\\pi_dates_wp_date_format_cb',
        'pi-dates-settings',
        'pi_dates_main_section'
    );

    add_settings_field(
        'pi_dates_wp_time_format',
        'Time Format',
        'PiDates\\pi_dates_wp_time_format_cb',
        'pi-dates-settings',
        'pi_dates_main_section'
    );
}

add_action('admin_init', 'PiDates\\pi_dates_register_settings');

function pi_dates_main_section_cb() {
    echo '<p>Pi Dates uses your WordPress time zone, date, and time settings.</p>';
}

function pi_dates_wp_timezone_cb() {
    $timezone = wp_timezone_string();
    echo '<p>' . esc_attr($timezone) . '</p>';
}

function pi_dates_wp_date_format_cb() {
    $date_format = get_option('date_format');
    echo '<p>' . esc_attr($date_format) . '</p>';
}

function pi_dates_wp_time_format_cb() {
    $time_format = get_option('time_format');
    echo '<p>' . esc_attr($time_format) . '</p>';
}

// The HTML for the settings page
function render_settings_page() {
    ?>
<div class="wrap">
<h1><?php echo \esc_html(\get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            \settings_fields('pi_dates_options');
            \do_settings_sections('pi-dates-settings');
            ?>
    </form>
    <p>To change these settings, please visit the <a href="<?php echo \admin_url('options-general.php'); ?>">WordPress General Settings</a> page.</p>

    <h2>Available Shortcodes</h2>
    <table class="widefat">
        <thead>
            <tr>
                <th>Shortcode</th>
                <th>Description</th>
                <th>Example Output</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>[pi_year]</code></td>
                <td>Current year in YYYY format</td>
                <td><?php echo \do_shortcode('[pi_year]'); ?></td>
            </tr>
            <tr>
                <td><code>[pi_yr]</code></td>
                <td>Current year in YY format</td>
                <td><?php echo \do_shortcode('[pi_yr]'); ?></td>
            </tr>
            <tr>
                <td><code>[pi_znum_month]</code></td>
                <td>Current month in 01 to 12 format</td>
                <td><?php echo \do_shortcode('[pi_znum_month]'); ?></td>
            </tr>
            <tr>
                <td><code>[pi_num_month]</code></td>
                <td>Current month in 1 to 12 format</td>
                <td><?php echo \do_shortcode('[pi_num_month]'); ?></td>
            </tr>
            <tr>
                <td><code>[pi_abr_month]</code></td>
                <td>Abbreviated month name (e.g., Jan)</td>
                <td><?php echo \do_shortcode('[pi_abr_month]'); ?></td>
            </tr>
            <tr>
                <td><code>[pi_month]</code></td>
                <td>Full month name</td>
                <td><?php echo \do_shortcode('[pi_month]'); ?></td>
            </tr>
            <tr>
                <td><code>[pi_znum_day]</code></td>
                <td>Current day in 01 to 31 format</td>
                <td><?php echo \do_shortcode('[pi_znum_day]'); ?></td>
            </tr>
            <tr>
                <td><code>[pi_num_day]</code></td>
                <td>Current day in 1 to 31 format</td>
                <td><?php echo \do_shortcode('[pi_num_day]'); ?></td>
            </tr>
            <tr>
                <td><code>[pi_zord_day]</code></td>
                <td>Current day with ordinal (01st, 02nd, etc.)</td>
                <td><?php echo \do_shortcode('[pi_zord_day]'); ?></td>
            </tr>
            <tr>
                <td><code>[pi_ord_day]</code></td>
                <td>Current day with ordinal (1st, 2nd, etc.)</td>
                <td><?php echo \do_shortcode('[pi_ord_day]'); ?></td>
            </tr>
            <tr>
                <td><code>[pi_abr_day]</code></td>
                <td>Abbreviated day name (e.g., Mon)</td>
                <td><?php echo \do_shortcode('[pi_abr_day]'); ?></td>
            </tr>
            <tr>
                <td><code>[pi_day]</code></td>
                <td>Full day name</td>
                <td><?php echo \do_shortcode('[pi_day]'); ?></td>
            </tr>
            <tr>
                <td><code>[pi_date_d]</code></td>
                <td>Day and date (e.g., Monday the 21st)</td>
                <td><?php echo \do_shortcode('[pi_date_d]'); ?></td>
            </tr>
            <tr>
                <td><code>[pi_date_m]</code></td>
                <td>Month and date (e.g., 21st of May)</td>
                <td><?php echo \do_shortcode('[pi_date_m]'); ?></td>
            </tr>
            <tr>
                <td><code>[pi_date_dm]</code></td>
                <td>Day, month and date (e.g., Monday the 21st of May)</td>
                <td><?php echo \do_shortcode('[pi_date_dm]'); ?></td>
            </tr>
        </tbody>
    </table>
</div>
<?php
}