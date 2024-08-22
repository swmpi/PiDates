<?php
/*
Plugin Name:  Pi Dates
Description:  A collection of useful shortcodes for displaying days, dates, and times.
Version:      0.2.2
Author:       sm314
Author:       https://sm314.com
License:      GPL v2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Copyright (C) 2024 sm314.com
This program is free software; you can distrubye it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
*/
// Exit if accessed directly.

namespace PiDates;

if (!defined('ABSPATH')) { 
    exit; }

final class Pi_Dates
{
    const VERSION = '0.2.2';
    const REQUIRED_WP_VERSION = '5.3';
    private static $instance;
    private function __construct() {
        if (!$this->check_wp_version()) {
            return; // Stop initialization if version check fails
        }
        $this->define_constants();
        $this->load_dependencies();
        $this->add_hooks();
    }
    public static function instance() {
        if (!isset(self::$instance) && !(self::$instance instanceof Pi_Dates)) {
            self::$instance = new Pi_Dates();
        }
        return self::$instance;
    }
    private function check_wp_version() {
        global $wp_version;

        if (version_compare($wp_version, self::REQUIRED_WP_VERSION, '<')) {
            $wpv1 = esc_html($wp_version);
            add_action('admin_notices', function() use ($wpv1) {
                echo '<div class="error">';
                echo '<p><strong>Pi Dates plugin error:</strong> Your site is running WordPress version ' . $wpv1 . ', but this plugin requires version 5.3 or higher.</p>';
                echo '<p>Please update your WordPress installation or deactivate the Pi Dates plugin.</p>';
                echo '</div>';
            });
            // Optionally deactivate the plugin
            add_action('admin_init', function() {
                deactivate_plugins(plugin_basename(PI_DATES_FILE));
            });
            return false;
        }
        return true;
    }
    // Define the required plugin constants.
    private function define_constants()
    {
    define( 'PI_DATES_VERSION', '0.1.2' );
    define( 'PI_DATES_FILE', __FILE__ );
    define( 'PI_DATES_PATH', plugin_dir_path( PI_DATES_FILE ) );
    define( 'PI_DATES_URL', plugin_dir_url( PI_DATES_FILE ) );
    define( 'PI_DATES_BASENAME', plugin_basename( PI_DATES_FILE ) );
    }
    private function load_dependencies()
    {
    //      require_once PI_DATES_PATH . 'includes/date-functions.php';
    //      require_once PI_DATES_PATH . 'includes/time-functions.php';
        require_once PI_DATES_PATH . 'pi-dates-settings.php';
        require_once PI_DATES_PATH . 'pi-dates-shortcodes.php';
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    private function add_hooks() {
        add_action('admin_menu', array($this, 'add_settings_page'));
        add_action('admin_init', array($this, 'register_settings'));
    }
    public function add_settings_page() {
        add_options_page(
            'Pi Dates Settings',
            'Pi Dates',
            'manage_options',
            'pi-dates-settings',
            array($this, 'render_settings_page')
        );
    }    
    public function render_settings_page() {
        render_settings_page();
    }
    public function register_settings() {
        // This will be implemented in pi-dates-settings.php
    }

    public function activate()
    {
        if (!$this->check_wp_version()) {
            return; // Don't proceed with activation if version check fails
        }
        // Add default options
        add_option('pi_dates_version', self::VERSION);
    }
    public function deactivate()
    {
        delete_option('pi_dates_version');
    }
}
function pi_dates_init()
{
    Pi_Dates::instance();
}
add_action('plugins_loaded', 'PiDates\\pi_dates_init');