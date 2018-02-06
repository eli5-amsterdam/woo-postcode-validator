<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.eli5.io
 * @since             1.0.0
 * @package           Woocommerce_Postcode_Validator
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Postcode Validator
 * Description:       WooCommerce Postcode Validator lets you validate Dutch postcodes and auto-fill the address for the filled in postcode.
 * Version:           1.0.0
 * Author:            Eli5
 * Author URI:        https://www.eli5.io
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocommerce-postcode-validator
 * Domain Path:       /languages
 *
 * WC requires at least: 3.0
 * WC tested up to: 3.2.6
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('PLUGIN_NAME_VERSION', '1.0.0');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-woocommerce-postcode-validator.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woocommerce_postcode_validator()
{
    if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
        $plugin = new Woocommerce_Postcode_Validator();
        $plugin->run();
    } else {
        add_action('admin_notices', 'fallback_notice');
    }
}

/**
 * WooCommerce fallback notice.
 *
 * @return string Fallack notice.
 */
function fallback_notice()
{
    $message = '<div class="error">';
    $message .= '<p>' . sprintf(__('Woocommerce Postcode Validator depends on <a href="%s">WooCommerce</a> to work!', 'woocommerce-postcode-validator'), 'http://wordpress.org/extend/plugins/woocommerce/') . '</p>';
    $message .= '</div>';

    echo $message;
}

run_woocommerce_postcode_validator();
