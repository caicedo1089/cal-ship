<?php
/**
 * @package calship
 */

/*
 Plugin Name: Calculation Shipping
 Plugin URI: https://github.com/caicedo1089/cal-ship
 Description:  Plugin que realiza el cálculo estandar de los envíos que permite configuración de sus valores
 Version: 0.1.0
 Author: Pedro Caicedo
 Author URI: http://pcaicedo.com
 License: MIT
 License URI:  http://www.opensource.org/licenses/mit-license.php
 Domain Path: /languages
 Text Domain: calculation-ship
 */
/*
The MIT License (MIT)

Copyright (c) 2015 Pedro Caicedo

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
 */

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( 'CALSHIP_VERSION', '0.2.0' );
define( 'CALSHIP_MINIMUM_WP_VERSION', '3.2' );
define( 'CALSHIP_PLUGIN_URL', plugin_dir_url( wp_normalize_path( __FILE__ ) ) );
define( 'CALSHIP_PLUGIN_DIR', plugin_dir_path( wp_normalize_path( __FILE__ ) ) );
define( 'CALSHIP_DELETE_LIMIT', 100000 );
define( 'CALSHIP_TABLE_NAME', 'calship' );

//wp_normalize_path( __FILE__ ) to win (http://wordpress.stackexchange.com/questions/44046/php-fatal-error-when-using-plugin-basename)
register_activation_hook( wp_normalize_path( __FILE__ ), array( 'CalShip', 'plugin_activation' ) );
register_deactivation_hook( wp_normalize_path( __FILE__ ), array( 'CalShip', 'plugin_deactivation' ) );

require_once( CALSHIP_PLUGIN_DIR . 'includes/class.calship.php' );
add_action( 'init', array( 'CalShip', 'init' ) );

if ( is_admin() ) {
	require_once( CALSHIP_PLUGIN_DIR . 'admin/class.calship-admin.php' );
	add_action( 'init', array( 'CalShip_Admin', 'init' ) );
}
