<?php
/**
 * Test the primary plugin file
 *
 * @package ChrisWiegman\chriswiegman_plugin
 */

namespace ChrisWiegman\chriswiegman_plugin\Tests;

use PHPUnit\Framework\TestCase;
use Brain\Monkey;

/**
 * Test the main plugin file
 */
class TestChrisWiegman extends TestCase {

	protected function setUp(): void {
        parent::setUp();
        Monkey\setUp();
    }

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

	/**
	 * Test loader function
	 */
	public function testPluginLoader(): void {
		Monkey\Functions\expect( 'load_plugin_textdomain' )->once();

		cw_plugin_plugin_loader();

		$this->assertTrue( true );
		// Dummy assertion as we're relying on expectations above.
	}
}
