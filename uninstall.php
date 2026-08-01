<?php
/**
 * Pi Dates Uninstall
 *
 * Runs when the plugin is deleted through the WordPress admin. WordPress loads
 * this file on its own, without loading the plugin, so nothing defined in
 * pi-dates.php is available here and option names are repeated as literals.
 *
 * The file is namespaced to match the rest of the plugin, and the work is held
 * in functions rather than run at file scope, so that nothing here is declared
 * in the global namespace. Calls to WordPress functions resolve to the global
 * namespace automatically through PHP's fallback for unqualified function names.
 */

namespace PiDates;

// Exit if WordPress is not uninstalling this plugin.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

if (!function_exists(__NAMESPACE__ . '\\pi_dates_delete_plugin_data')) {
    // Removes every option the plugin creates, for the current site.
    function pi_dates_delete_plugin_data() {
        delete_option('pi_dates_version');
        delete_option('pi_dates_options');
    }
}

if (!function_exists(__NAMESPACE__ . '\\pi_dates_run_uninstall')) {
    /*
    Options are stored per site, so on a network each site has to be cleaned in
    turn. switch_to_blog() changes which site the option functions read and
    write, and restore_current_blog() puts it back.
    */
    function pi_dates_run_uninstall() {
        if (!is_multisite()) {
            pi_dates_delete_plugin_data();
            return;
        }

        $site_ids = get_sites(array(
            'fields' => 'ids',
            'number' => 0,
        ));

        foreach ($site_ids as $site_id) {
            switch_to_blog($site_id);
            pi_dates_delete_plugin_data();
            restore_current_blog();
        }
    }
}

pi_dates_run_uninstall();
