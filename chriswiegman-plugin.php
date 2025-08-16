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

	add_action( 'rss2_item', 'cw_plugin_add_featured_image_to_feed' );
}

/**
 * Adds the featured image to the RSS feed
 *
 * @since 2.0.0
 */
function cw_plugin_add_featured_image_to_feed() {
	global $post;
	if ( has_post_thumbnail( $post->ID ) ) {
		$attachment_id  = get_post_thumbnail_id( $post->ID );
		$featured_image = wp_get_attachment_image_src( $attachment_id, 'post-thumbnail' );
		$url            = $featured_image[0];
		$length         = filesize( get_attached_file( $attachment_id ) );
		$type           = get_post_mime_type( $attachment_id );

		printf( '<enclosure url="%s" length="%d" type="%s" />', esc_url( $url ), esc_attr( $length ), esc_attr( $type ) );
	}
}

add_action( 'plugins_loaded', 'cw_plugin_plugin_loader' );
