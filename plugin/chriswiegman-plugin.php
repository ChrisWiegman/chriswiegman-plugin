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

add_action( 'plugins_loaded', 'cw_chriswiegman_plugin_loader' );
