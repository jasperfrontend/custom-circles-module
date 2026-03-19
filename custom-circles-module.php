<?php
/**
 * Plugin Name: Orbis - Circle Stacker for Beaver Builder
 * Plugin URI: https://github.com/jasperfrontend/custom-circles-module
 * Description: A Beaver Builder module for stacking decorative circles around images or text content.
 * Version: 1.2.0
 * Author: Jasper
 * Author URI: https://github.com/jasperfrontend
 * License: GPL2
 * Text Domain: orbis
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ORBIS_VERSION', '1.2.0' );
define( 'ORBIS_FILE', __FILE__ );
define( 'ORBIS_DIR', plugin_dir_path( __FILE__ ) );
define( 'ORBIS_URL', plugins_url( '/', __FILE__ ) );

require_once ORBIS_DIR . 'classes/class-orbis-loader.php';
require_once ORBIS_DIR . 'classes/class-orbis-updater.php';

new Orbis_GitHub_Updater( ORBIS_FILE );
