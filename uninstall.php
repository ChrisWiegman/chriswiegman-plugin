<?php
/**
 * ChrisWiegman.com Functionality uninstaller
 *
 * Used when clicking "Delete" from inside of WordPress's plugins page.
 *
 * @package ChrisWiegman\ChrisWiegman_com_Functionality
 *
 * @since   1.0.0
 *
 * @author  Chris Wiegman <contact@chriswiegman.com>
 */

namespace ChrisWiegman\ChrisWiegman_com_Functionality\Uninstall;

/**
 * Initialize uninstaller
 *
 * Perform some checks to make sure plugin can/should be uninstalled
 *
 * @since 1.0.0
 */
function perform_uninstall() {

	// Exit if accessed directly.
	if ( ! defined( 'ABSPATH' ) ) {
		exit_uninstaller();
	}

	// Not uninstalling.
	if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
		exit_uninstaller();
	}

	// Not uninstalling.
	if ( ! WP_UNINSTALL_PLUGIN ) {
		exit_uninstaller();
	}

	// Not uninstalling this plugin.
	if ( dirname( WP_UNINSTALL_PLUGIN ) !== dirname( plugin_basename( __FILE__ ) ) ) {
		exit_uninstaller();
	}

	// Uninstall WordPress Plugin Starter.
	clean_data();
}

/**
 * Cleanup options
 *
 * Deletes plugin options and post_meta.
 *
 * @since 1.0.0
 */
function clean_data() {

}

/**
 * Exit uninstaller
 *
 * Gracefully exit the uninstaller if we should not be here
 *
 * @since 1.0.0
 */
function exit_uninstaller() {

	status_header( 404 );
	exit;

}

perform_uninstall();
