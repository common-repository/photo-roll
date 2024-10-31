<?php

/**
 *
 * @since             1.0.0
 * @package           Instantgram
 *
 * @wordpress-plugin
 * Plugin Name:       Photo roll
 * Description:       This plugin retrives instagram photos. All you need is your profile name, that's it.
 * Version:           1.0.0
 * Author:            InstatGram
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       instantgram
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PRI_INSTANTGRAM_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function pri_activate_instantgram() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-instantgram-activator.php';
    PRI_Instantgram_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-instantgram-deactivator.php
 */
function pri_deactivate_instantgram() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-instantgram-deactivator.php';
    PRI_Instantgram_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'pri_activate_instantgram' );
register_deactivation_hook( __FILE__, 'pri_deactivate_instantgram' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-instantgram.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_pri_instantgram() {

	$plugin = new PRI_Instantgram();
	$plugin->run();

}
run_pri_instantgram();
