<?php

/**
 * Test the primary plugin file
 *
 * @package ChrisWiegman\ChrisWiegman.com_Functionality
 */

namespace ChrisWiegman\ChrisWiegman.com_Functionality\Tests;

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
		Monkey\Functions\expect( 'plugin_dir_url' )->once();
		Monkey\Functions\expect( 'get_file_data' )->once();

		cw_chriswiegman_plugin_loader();

		$this->assertTrue( true );
		// Dummy assertion as we're relying on expectations above.

	}

	public function test_autoloader_registered() {
		$this->assertContains( 'cw_chriswiegman_plugin_autoloader', spl_autoload_functions() );
	}

	public function test_autoloader() {

		$test_classes = array(
			'ChrisWiegman\ChrisWiegman.com_Functionality\Class_One' => '/app/plugin/lib/class-class-one.php',
			'ChrisWiegman\ChrisWiegman.com_Functionality\Sub_Classes\Class_Two' => '/app/plugin/lib/Sub_Classes/class-class-two.php',
			'Class_Three' => '',
		);

		foreach ( $test_classes as $test_class => $class_file ) {

			$file = cw_chriswiegman_plugin_get_class_file( $test_class );

			$this->assertEquals( $class_file, $file );

		}
	}
}
