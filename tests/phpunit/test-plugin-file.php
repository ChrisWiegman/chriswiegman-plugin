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

		cw_chriswiegman_plugin_loader();

		$this->assertTrue( true );
		// Dummy assertion as we're relying on expectations above.

	}

	public function test_autoloader_registered() {
		$this->assertContains( 'cw_chriswiegman_plugin_autoloader', spl_autoload_functions() );
	}

	public function test_autoloader() {

		$test_classes = array(
			'ChrisWiegman\ChrisWiegman_com_Functionality\Class_One' => '/app/src/lib/class-class-one.php',
			'ChrisWiegman\ChrisWiegman_com_Functionality\Sub_Classes\Class_Two' => '/app/src/lib/Sub_Classes/class-class-two.php',
			'Class_Three' => '',
		);

		foreach ( $test_classes as $test_class => $class_file ) {

			$file = cw_chriswiegman_plugin_get_class_file( $test_class );

			$this->assertEquals( $class_file, $file );

		}
	}
}
