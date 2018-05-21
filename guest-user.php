<?php

/**
 * The plugin bootstrap file
 *
 * @link              https://github.com/Neshable/wp-guest-user
 * @since             1.0.0
 * @package           Guest_User
 *
 * @wordpress-plugin
 * Plugin Name:       Add Guest User
 * Plugin URI:        none
 * Description:       Add a guest user with no email adress. Suitable for adding people without account login.
 * Version:           1.0.0
 * Author:            Nesho Sabakov
 * Author URI:        http://softsab.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       guest-user
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
define( 'PLUGIN_NAME_VERSION', '1.0.0' );


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-guest-user.php';


function run_guest_user() {
	$plugin = new Guest_User();
}

run_guest_user();
