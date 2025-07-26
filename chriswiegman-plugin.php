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

	// Remove extra hooks
	remove_action( 'wp_head', 'wp_generator' );

	// Add new folders and actions.
	add_action( 'send_headers', 'cw_plugin_action_send_headers' );
}

/**
 * Action send_headers
 *
 * Set the security headers.
 *
 * @since 2.0.0
 */
function cw_plugin_action_send_headers() {
	if ( ! is_admin() ) {
		header( 'Strict-Transport-Security: max-age=15768000' );
		header( 'x-content-type-options: nosniff' );
		header( 'x-permitted-cross-domain-policies: none' );
		header( 'x-xss-protection: 1; mode=block' );
		header( 'x-frame-options: SAMEORIGIN' );
	}
}

add_action( 'plugins_loaded', 'cw_plugin_plugin_loader' );
