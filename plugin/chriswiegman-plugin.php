<?php
/**
 * Plugin Name: ChrisWiegman.com Functionality
 * Plugin URI: https://chriswiegman.com/
 * Description: Added functionality for ChrisWiegman.com.
 * Version: 1.3.0
 * Text Domain: chriswiegman-plugin
 * Domain Path: /languages
 * Author: Chris Wiegman
 * Author URI: https://wpengine.com/
 * License: GPLv2
 *
 * @package ChrisWiegman\ChrisWiegman_com_Functionality
 */

/**
 * Load plugin functionality.
 *
 * @since 1.0.0
 */
function cw_chriswiegman_plugin_loader() {

	// Load the text domain.
	load_plugin_textdomain( 'chriswiegman-plugin', false, dirname( dirname( __FILE__ ) ) . '/languages' );

	add_action( 'send_headers', 'cw_action_send_headers' );

	add_filter( 'jetpack_comment_subscription_form', 'cw_filter_jetpack_comment_subscription_form' );
	add_filter( 'jetpack_sso_bypass_login_forward_wpcom', '__return_true' );

	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_generator' );

}

/**
 * Filter jetpack_comment_subscription_form
 *
 * Fixes the CSS on the subscription form
 *
 * @since 1.2.0
 *
 * @param string $str Comment Subscription form HTML output.
 *
 * @return string
 */
function cw_filter_jetpack_comment_subscription_form( $str ) {

	return preg_replace( '/(<[^>]+) style=".*?"/i', '$1', $str );

}

/**
 * Action send_headers
 *
 * Set the security headers.
 *
 * @since 1.0.0
 */
function cw_action_send_headers() {

	if ( ! is_admin() ) {
		header( 'Strict-Transport-Security: max-age=15768000' );
		header( 'x-content-type-options: nosniff' );
		header( 'x-permitted-cross-domain-policies: none' );
		header( 'x-xss-protection: 1; mode=block' );
		header( 'x-frame-options: SAMEORIGIN' );
	}
}

add_action( 'plugins_loaded', 'cw_chriswiegman_plugin_loader' );
