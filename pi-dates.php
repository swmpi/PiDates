<?php
/*
Plugin Name:  Pi Dates
Plugin URI:   https://sm314.com/pi-dates/
Description:  A collection of useful shortcodes for displaying days, dates, and times.
Version:      0.3.0
Requires at least: 5.3
Requires PHP: 7.0
Author:       sm314
Author URI:   https://sm314.com
Text Domain:  pi-dates
Domain Path:  /languages
License:      GPL v2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
This program is free software; you can distribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
*/
// Exit if accessed directly.

namespace PiDates;

if (!defined('ABSPATH')) { 
    exit; }

final class Pi_Dates
{
    const VERSION = '0.3.0';
    const REQUIRED_WP_VERSION = '5.3';
    private static $instance;
    private function __construct() {
        if (!self::check_wp_version()) {
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
    /*
    Static so it can be called from the activation hook, which fires before
    the class is instantiated.
    */
    private static function check_wp_version() {
        global $wp_version;

        if (version_compare($wp_version, self::REQUIRED_WP_VERSION, '<')) {
            $current  = $wp_version;
            $required = self::REQUIRED_WP_VERSION;
            /*
            The strings are translated inside the closure rather than out here.
            This method can run during activation and on plugins_loaded, both of
            which are before init, and translations are not ready until init.
            admin_notices fires long after, so it is safe at that point.
            */
            add_action('admin_notices', function() use ($current, $required) {
                echo '<div class="notice notice-error">';
                echo '<p><strong>' . esc_html__('Pi Dates plugin error:', 'pi-dates') . '</strong> ';
                echo esc_html(pi_dates_sprintf(
                    /* translators: 1: WordPress version running on the site, 2: minimum WordPress version required by the plugin */
                    __('Your site is running WordPress version %1$s, but this plugin requires version %2$s or higher.', 'pi-dates'),
                    array($current, $required),
                    'Your site is running WordPress version %1$s, but this plugin requires version %2$s or higher.'
                ));
                echo '</p>';
                echo '<p>' . esc_html__('Please update your WordPress installation or deactivate the Pi Dates plugin.', 'pi-dates') . '</p>';
                echo '</div>';
            });
            // Optionally deactivate the plugin
            // Uses __FILE__ directly because define_constants() has not run at this point.
            add_action('admin_init', function() {
                /*
                admin_init also fires on admin-ajax.php requests from any logged in
                user, and deactivate_plugins() performs no capability check of its
                own, so the check has to happen here.
                */
                if (!current_user_can('activate_plugins')) {
                    return;
                }
                deactivate_plugins(plugin_basename(__FILE__));
            });
            return false;
        }
        return true;
    }
    // Define the required plugin constants.
    private function define_constants()
    {
    // Guarded so a second include of this file cannot raise a redefinition notice.
    defined( 'PI_DATES_VERSION' )  || define( 'PI_DATES_VERSION', self::VERSION );
    defined( 'PI_DATES_FILE' )     || define( 'PI_DATES_FILE', __FILE__ );
    defined( 'PI_DATES_PATH' )     || define( 'PI_DATES_PATH', plugin_dir_path( PI_DATES_FILE ) );
    defined( 'PI_DATES_URL' )      || define( 'PI_DATES_URL', plugin_dir_url( PI_DATES_FILE ) );
    defined( 'PI_DATES_BASENAME' ) || define( 'PI_DATES_BASENAME', plugin_basename( PI_DATES_FILE ) );
    }
    private function load_dependencies()
    {
    //      require_once PI_DATES_PATH . 'includes/date-functions.php';
    //      require_once PI_DATES_PATH . 'includes/time-functions.php';
        require_once PI_DATES_PATH . 'pi-dates-settings.php';
        require_once PI_DATES_PATH . 'pi-dates-shortcodes.php';
    }
    private function add_hooks() {
        // Settings registration is handled on admin_init inside pi-dates-settings.php.
        add_action('admin_menu', array($this, 'add_settings_page'));
        add_action('admin_init', array($this, 'maybe_upgrade'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }

    /*
    Loads the stylesheet on the Pi Dates settings screen only. WordPress passes
    the current screen's hook suffix, which for a page added with
    add_options_page() and the slug pi-dates-settings is
    settings_page_pi-dates-settings. Checking it here rather than reading
    $_GET['page'] means the sheet stays off the other admin screens.

    PI_DATES_VERSION is passed as the version so the URL changes with each
    release and browsers pick up the new file without a hard refresh.
    */
    public function enqueue_admin_assets($hook_suffix) {
        if ('settings_page_pi-dates-settings' !== $hook_suffix) {
            return;
        }

        wp_enqueue_style(
            'pi-dates-admin',
            PI_DATES_URL . 'css/pi-dates-admin.css',
            array(),
            PI_DATES_VERSION
        );
    }

    /*
    Keeps the stored version in step with the code. activate() uses add_option(),
    which will not overwrite an existing value, and the option now survives
    deactivation, so without this the stored version would stay at whatever was
    first installed. Runs on admin requests only; the comparison exits early on
    almost all of them. Any future data migrations belong here, keyed off the
    stored value before it is overwritten.
    */
    public function maybe_upgrade() {
        $stored = get_option('pi_dates_version');

        if (self::VERSION === $stored) {
            return;
        }

        if (false !== $stored) {
            // Migrations for sites coming from an earlier version go here, e.g.
            // if (version_compare($stored, '0.4.0', '<')) { ... }
        }

        update_option('pi_dates_version', self::VERSION);
    }

    public function add_settings_page() {
        add_options_page(
            __('Pi Dates Settings', 'pi-dates'),
            __('Pi Dates', 'pi-dates'),
            'manage_options',
            'pi-dates-settings',
            array($this, 'render_settings_page')
        );
    }    
    public function render_settings_page() {
        \PiDates\render_settings_page();
    }

    /*
    Static because the activation hook is registered at file scope, before any
    instance of this class exists.

    There is no matching deactivate() method. Deactivating a plugin is routinely
    done to troubleshoot a site, so the plugin's data is left in place; it is
    removed in uninstall.php when the plugin is actually deleted.
    */
    public static function activate()
    {
        if (!self::check_wp_version()) {
            return; // Don't proceed with activation if version check fails
        }
        // Add default options
        add_option('pi_dates_version', self::VERSION);
    }
}

/*
Registered at file scope, not inside the class. During activation WordPress
includes the plugin file and then immediately fires the activation hook, both
after plugins_loaded has already run, so anything registered from the class
constructor would be too late to catch it.
*/
register_activation_hook(__FILE__, array(__NAMESPACE__ . '\\Pi_Dates', 'activate'));

/*
sprintf() for strings that come out of a translation file. A translation that
introduces a placeholder the caller does not supply makes PHP 8 throw
ArgumentCountError, which would take down whichever page the string appears on.
On any failure the untranslated source format is used instead, so the worst case
is English text rather than a fatal error.

$format        already translated, and already escaped if the result will not be
               escaped afterwards
$args          values for the placeholders, in order
$source_format the original English format, used only if $format is unusable

Defined at file scope because the version notice needs it even when the version
check has stopped the rest of the plugin from loading.
*/
function pi_dates_sprintf($format, array $args, $source_format)
{
    try {
        // PHP 7 warns and returns false here, PHP 8 throws; both are handled.
        $result = @vsprintf($format, $args);
        if (false !== $result) {
            return $result;
        }
    } catch (\Throwable $e) {
        // Fall through to the source format.
    }

    $result = @vsprintf($source_format, $args);

    return (false === $result) ? '' : $result;
}

/*
No load_plugin_textdomain() call. Since WordPress 4.6 translations for plugins
hosted on wordpress.org are loaded automatically, just in time, from the text
domain declared in the plugin header. Calling it manually is redundant there.

The one thing this relies on is that no translation function runs before init.
Every __() and esc_html__() in this plugin sits inside a callback that fires on
admin_notices, admin_menu, admin_init or shortcode render, all of which are
after init, so nothing triggers the early loading notice added in 6.7.
*/
function pi_dates_init()
{
    Pi_Dates::instance();
}
add_action('plugins_loaded', 'PiDates\\pi_dates_init');
