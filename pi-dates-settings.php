<?php
/**
 * Pi Dates Settings
 *
 * This file contains the settings page and settings registration for the Pi Dates plugin.
 */
namespace PiDates;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/*
Everything in this file runs on admin_init or later, so translations are always
available by the time these strings are evaluated.
*/
function pi_dates_register_settings() {

    register_setting(
        'pi_dates_options',
        'pi_dates_options',
        array(
            'type'              => 'array',
            'sanitize_callback' => 'PiDates\\pi_dates_sanitize_options',
            'default'           => array(),
        )
    );

    /*
    The section title is deliberately empty. WordPress only outputs the <h2>
    when a title is set, and the page already has an <h1> of "Pi Dates Settings"
    from the menu registration, so a title here would repeat it.
    */
    add_settings_section(
        'pi_dates_main_section',
        '',
        'PiDates\\pi_dates_main_section_cb',
        'pi-dates-settings'
    );

    add_settings_field(
        'pi_dates_wp_timezone',
        esc_html__('Time Zone', 'pi-dates'),
        'PiDates\\pi_dates_wp_timezone_cb',
        'pi-dates-settings',
        'pi_dates_main_section',
        array('label_for' => 'pi_dates_wp_timezone')
    );

    add_settings_field(
        'pi_dates_wp_date_format',
        esc_html__('Date Format', 'pi-dates'),
        'PiDates\\pi_dates_wp_date_format_cb',
        'pi-dates-settings',
        'pi_dates_main_section',
        array('label_for' => 'pi_dates_wp_date_format')
    );

    add_settings_field(
        'pi_dates_wp_time_format',
        esc_html__('Time Format', 'pi-dates'),
        'PiDates\\pi_dates_wp_time_format_cb',
        'pi-dates-settings',
        'pi_dates_main_section',
        array('label_for' => 'pi_dates_wp_time_format')
    );
}

add_action('admin_init', 'PiDates\\pi_dates_register_settings');

/*
The settings page is read only and mirrors the WordPress General Settings
values, so nothing is currently written to pi_dates_options. This callback
exists so the option group is registered correctly and is ready for the
first writable setting.
*/
function pi_dates_sanitize_options($input) {
    if (!is_array($input)) {
        return array();
    }

    $output = array();
    foreach ($input as $key => $value) {
        if (is_scalar($value)) {
            $output[sanitize_key($key)] = sanitize_text_field($value);
        }
    }

    return $output;
}

function pi_dates_main_section_cb() {
    echo '<p>' . esc_html__('Pi Dates uses your WordPress time zone, date, and time settings. To change these settings, please use the WordPress General Settings page.', 'pi-dates') . '</p>';
}

/*
Shared renderer for the three read only fields. Keeping the markup in one place
means the "Set in General Settings" string is translated once rather than three
times over.

$id must match the label_for passed to add_settings_field(). WordPress renders
the field title as <label for="..."> only when label_for is set, and the
form-table wrapper it generates carries role="presentation", which strips the
table semantics that would otherwise associate the row header with the input.
Without the pairing these fields have no accessible name at all.
*/
function pi_dates_readonly_field($id, $value) {
    $link = '<a href="' . esc_url(admin_url('options-general.php')) . '">'
        . esc_html__('General Settings', 'pi-dates')
        . '</a>';

    echo '<input type="text" id="' . esc_attr($id) . '" class="pi-dates-readonly" value="' . esc_attr($value) . '" readonly>';
    echo '<p class="description">';
    /*
    The format is escaped here rather than the result, because the result
    deliberately contains an anchor tag. $link is built from esc_url() and
    esc_html() above, so it is safe to insert unescaped.
    */
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Format escaped by esc_html__() on the next line, and the only argument is $link, assembled above from esc_url() and esc_html__(). pi_dates_sprintf() interpolates and never introduces markup of its own.
    echo pi_dates_sprintf(
        /* translators: %s: link to the WordPress General Settings screen, with the link text "General Settings" */
        esc_html__('Set in %s', 'pi-dates'),
        array($link),
        'Set in %s'
    );
    echo '</p>';
}

/*
WordPress passes the args array registered with add_settings_field(), so the id
is read back from label_for rather than repeated as a literal. That keeps the
label and the input in sync if a field is ever renamed.
*/
function pi_dates_wp_timezone_cb($args) {
    pi_dates_readonly_field($args['label_for'], wp_timezone_string());
}

function pi_dates_wp_date_format_cb($args) {
    pi_dates_readonly_field($args['label_for'], get_option('date_format'));
}

function pi_dates_wp_time_format_cb($args) {
    pi_dates_readonly_field($args['label_for'], get_option('time_format'));
}

/*
The shortcodes shown in the reference table, in display order. Held as data
rather than repeated markup so each description is translated in one place and
a new shortcode only needs a line adding here.
*/
function pi_dates_shortcode_reference() {
    return array(
        'pi_year'       => __('Current year in YYYY format', 'pi-dates'),
        'pi_yr'         => __('Current year in YY format', 'pi-dates'),
        'pi_znum_month' => __('Current month in 01 to 12 format', 'pi-dates'),
        'pi_num_month'  => __('Current month in 1 to 12 format', 'pi-dates'),
        'pi_abr_month'  => __('Abbreviated month name (e.g., Jan)', 'pi-dates'),
        'pi_month'      => __('Full month name', 'pi-dates'),
        'pi_znum_day'   => __('Current day in 01 to 31 format', 'pi-dates'),
        'pi_num_day'    => __('Current day in 1 to 31 format', 'pi-dates'),
        'pi_zord_day'   => __('Current day with ordinal (01st, 02nd, etc.)', 'pi-dates'),
        'pi_ord_day'    => __('Current day with ordinal (1st, 2nd, etc.)', 'pi-dates'),
        'pi_abr_day'    => __('Abbreviated day name (e.g., Mon)', 'pi-dates'),
        'pi_day'        => __('Full day name', 'pi-dates'),
        'pi_date_d'     => __('Day and date (e.g., Monday the 21st)', 'pi-dates'),
        'pi_date_m'     => __('Month and date (e.g., 21st of May)', 'pi-dates'),
        'pi_date_dm'    => __('Day, month and date (e.g., Monday the 21st of May)', 'pi-dates'),
    );
}

// The HTML for the settings page
function render_settings_page() {
    if (!\current_user_can('manage_options')) {
        return;
    }
    ?>
<div class="wrap">
<h1><?php echo \esc_html(\get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            \settings_fields('pi_dates_options');
            \do_settings_sections('pi-dates-settings');
            ?>
    </form>
    <p><?php
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Format escaped by esc_html__() below, and the only argument is an anchor built inline from esc_url() and esc_html__(). pi_dates_sprintf() interpolates and never introduces markup of its own.
    echo pi_dates_sprintf(
        /* translators: %s: link to the WordPress General Settings screen, with the link text "WordPress General Settings" */
        \esc_html__('To change these settings, please visit the %s page.', 'pi-dates'),
        array(
            '<a href="' . \esc_url(\admin_url('options-general.php')) . '">'
                . \esc_html__('WordPress General Settings', 'pi-dates')
                . '</a>'
        ),
        'To change these settings, please visit the %s page.'
    );
    ?></p>

    <h2><?php echo \esc_html__('Available Shortcodes', 'pi-dates'); ?></h2>
    <table class="widefat striped pi-dates-shortcodes">
        <thead>
            <tr>
                <th><?php echo \esc_html__('Shortcode', 'pi-dates'); ?></th>
                <th><?php echo \esc_html__('Description', 'pi-dates'); ?></th>
                <th><?php echo \esc_html__('Example Output', 'pi-dates'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach (pi_dates_shortcode_reference() as $pi_dates_tag => $pi_dates_description) : ?>
            <tr>
                <td><code>[<?php echo \esc_html($pi_dates_tag); ?>]</code></td>
                <td><?php echo \esc_html($pi_dates_description); ?></td>
                <td><?php echo \do_shortcode('[' . $pi_dates_tag . ']'); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php
}
