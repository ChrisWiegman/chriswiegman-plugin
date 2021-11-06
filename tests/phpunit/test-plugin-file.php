<?php

/**
 * Test the primary plugin file
 *
 * @package ChrisWiegman\ChrisWiegman_com_Functionality
 */

namespace ChrisWiegman\ChrisWiegman_com_Functionality\Tests;

use PHPUnit\Framework\TestCase;
use Brain\Monkey;

/**
 * Test the main plugin file
 */
class PluginFileTest extends TestCase {

	protected function tearDown(): void {

		Monkey\tearDown();
		parent::tearDown();

	}

	/**
	 * Test loader function
	 */
	public function test_cw_chriswiegman_plugin_loader() {

		Monkey\Functions\expect( 'load_plugin_textdomain' )->once();

		$this->assertTrue( true );
		// Dummy assertion as we're relying on expectations above.

	}
}
