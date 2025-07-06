<?php
/**
 * Plugin Name: ChrisWiegman.com
 * Plugin URI: https://chriswiegman.com/
 * Description: Basic Functionality for ChrisWiegman.com
 * Version: 2.0.0
 * Text Domain: cw_plugin
 * Domain Path: /languages
 * Author: Chris Wiegman
 * Author URI: https://chriswiegman.com/
 * License: GPLv2
 *
 * @package ChrisWiegman\chriswiegman_plugin
 */

/**
 * Load plugin functionality.
 *
 * @since 2.0.0
 */
function cw_plugin_plugin_loader() {
	// Load the text domain.
	load_plugin_textdomain( 'cw_plugin', false, dirname( __DIR__ ) . '/languages' );
}

add_action( 'plugins_loaded', 'cw_plugin_plugin_loader' );
