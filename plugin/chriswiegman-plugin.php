<?php
/**
 * Plugin Name: ChrisWiegman.com Functionality
 * Plugin URI: https://chriswiegman.com
 * Description: Added functionality for ChrisWiegman.com
 * Version: 0.0.1
 * Text Domain: chriswiegman-plugin
 * Domain Path: /languages
 * Author: WP Engine
 * Author URI: https://wpengine.com/
 * License: GPLv2
 *
 * @package ChrisWiegman\ChrisWiegman.com_Functionality
 */

/**
 * Load plugin functionality.
 *
 * @since 1.0.0
 */
function cw_chriswiegman_plugin_loader() {

	$plugin_url  = plugin_dir_url( __FILE__ );
	$plugin_info = get_file_data( __FILE__, array( 'Version' => 'Version' ) );

	// Load the text domain.
	load_plugin_textdomain( 'chriswiegman-plugin', false, dirname( dirname( __FILE__ ) ) . '/languages' );

}

/**
 * Builds the class file name for the plugin
 *
 * @since 1.0.0
 *
 * @param string $class The name of the class to get.
 * @return string
 */
function cw_chriswiegman_plugin_get_class_file( $class ) {

	$prefix   = 'ChrisWiegman\\ChrisWiegman.com_Functionality\\';
	$base_dir = __DIR__ . '/lib/';

	$len = strlen( $prefix );

	if ( 0 !== strncmp( $prefix, $class, $len ) ) {
		return '';
	}

	$relative_class = substr( $class, $len );
	$file           = $base_dir . str_replace( '\\', '/', 'class-' . strtolower( str_replace( '_', '-', $relative_class ) ) ) . '.php';

	$relative_class_parts = explode( '\\', $relative_class );

	if ( 1 < count( $relative_class_parts ) ) {

		$class_file = $relative_class_parts[0] . '/class-' . strtolower( str_replace( '_', '-', $relative_class_parts[1] ) );
		$file       = $base_dir . str_replace( '\\', '/', $class_file ) . '.php';

	}

	return $file;

}

/**
 * Auto-loading functionality for the plugin features
 *
 * @since 1.0.0
 *
 * @param object $class The class to load.
 */
function cw_chriswiegman_plugin_autoloader( $class ) {

	$file = cw_chriswiegman_plugin_get_class_file( $class );

	if ( ! empty( $file ) && file_exists( $file ) ) {
		include $file;
	}
}

spl_autoload_register( 'cw_chriswiegman_plugin_autoloader' );

add_action( 'plugins_loaded', 'cw_chriswiegman_plugin_loader' );
