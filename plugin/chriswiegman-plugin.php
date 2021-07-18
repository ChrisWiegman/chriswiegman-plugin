<?php
/**
 * Plugin Name: ChrisWiegman.com Functionality
 * Plugin URI: https://chriswiegman.com
 * Description: Added functionality for ChrisWiegman.com
 * Version: 0.0.1
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

	$plugin_url  = plugin_dir_url( __FILE__ );
	$plugin_info = get_file_data( __FILE__, array( 'Version' => 'Version' ) );

	// Load the text domain.
	load_plugin_textdomain( 'chriswiegman-plugin', false, dirname( dirname( __FILE__ ) ) . '/languages' );

	add_action( 'send_headers', 'cw_action_send_headers' );
	add_action( 'wp_before_admin_bar_render', 'cw_action_wp_before_admin_bar_render' );
	add_action( 'admin_menu', 'cw_action_admin_menu' );
	add_action( 'init', 'cw_action_init', 100 );
	add_action( 'wp_head', 'cw_action_wp_head' );

	add_filter( 'wp_resource_hints', 'cw_filter_wp_resource_hints', 10, 2 );

	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'template_redirect', 'rest_output_link_header', 11 );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'rest_output_link_wp_head' );

}

/**
 * Action wp_head
 *
 * Output the site icon
 *
 * @since 1.0.0
 */
function cw_action_wp_head() {

	wp_site_icon();

}

/**
 * Action send_headers
 *
 * Set the security headers.
 *
 * @since 1.0.0
 */
function cw_action_send_headers() {

	header( 'Strict-Transport-Security: max-age=15768000' );
	header( 'x-content-type-options: nosniff' );
	header( 'x-permitted-cross-domain-policies: none' );
	header( 'x-xss-protection: 1; mode=block' );
	header( 'x-frame-options: SAMEORIGIN' );

}

/**
 * Removes comments from the admin bar
 *
 * @since 1.0.0
 */
function cw_action_wp_before_admin_bar_render() {

	global $wp_admin_bar;

	$wp_admin_bar->remove_menu( 'comments' );

}

/**
 * Removes comments from the admin menu
 *
 * @since 1.0.0
 */
function cw_action_admin_menu() {

	remove_menu_page( 'edit-comments.php' );

}

/**
 * Remove support for comments
 *
 * @since 1.0.0
 */
function cw_action_init() {

	remove_post_type_support( 'post', 'comments' );
	remove_post_type_support( 'page', 'comments' );
	add_image_size( 'avatar', 228, 228, true );

}

/**
 * Filter wp_resource hints
 *
 * Remove extra DNS prefecth links.
 *
 * @since 1.0.0
 *
 * @param array  $urls          URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed for, e.g. 'preconnect' or 'prerender'.
 *
 * @return array
 */
function cw_filter_wp_resource_hints( $urls, $relation_type ) {

	if ( 'dns-prefetch' !== $relation_type ) {
		return $urls;
	}

	unset( $urls[1] );

	return $urls;
}

add_action( 'plugins_loaded', 'cw_chriswiegman_plugin_loader' );
