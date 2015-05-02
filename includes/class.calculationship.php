<?php

class Calculationship {
	
	private static $initiated = false;
	private static $table_calship = '';
	private static $bd_version = '1.0';
	
	public static function init() {
		
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}
	
	/**
	 * Initializes WordPress hooks
	 */
	private static function init_hooks() {
		self::$initiated = true;
		
		//Update BD
		add_action( 'plugins_loaded', array( 'Calculationship', 'db_check_update' ) );
		
		add_action( 'wp_enqueue_scripts', array( 'Calculationship', 'load_resources' ) );
		
		add_shortcode( 'calculationship', array( 'Calculationship', 'load_shortcode' ) );
	}
	
	/**
	 * Attached to activate_{ plugin_basename( __FILES__ ) } by register_activation_hook()
	 * @static
	 */
	public static function plugin_activation() {

		
		if ( version_compare( $GLOBALS['wp_version'], CALCULATIONSHIP__MINIMUM_WP_VERSION, '<' ) ) {
			load_plugin_textdomain( 'calculationship' );
				
			$message = '<strong>'.sprintf(esc_html__( 'CalculationShip %s requires WordPress %s or higher.' , 'calculationship'), CALCULATIONSHIP_VERSION, CALCULATIONSHIP__MINIMUM_WP_VERSION ).'</strong> '.sprintf(__('Please <a href="%1$s">upgrade WordPress</a> to a current version, or <a href="%2$s">downgrade to version 0.2 of the CalculationShip plugin</a>.', 'calculationship'), 'https://codex.wordpress.org/Upgrading_WordPress', 'http://wordpress.org/extend/plugins/akismet/download/');
	
			Calculationship::bail_on_activation( $message );
		}else{
			Calculationship::db_install();
			Calculationship::db_install_data();
		}
	}
	
	/**
	 * Removes all connection options
	 * @static
	 */
	public static function plugin_deactivation() {
		global $wpdb;
		if ( self:: $table_calship == '')
			self::$table_calship = $wpdb->prefix . CALCULATIONSHIP_TABLE_NAME;

		$table_name = self::$table_calship;
		
		$sql = "DROP TABLE $table_name";
		$wpdb->query($sql);

		delete_option('calship_db_version');
	}
	
	private static function db_install() {
		global $wpdb;
		if ( self::$table_calship == '' )
			self::$table_calship = $wpdb->prefix . CALCULATIONSHIP_TABLE_NAME;
		
		$table_name = self::$table_calship;
	
		$charset_collate = $wpdb->get_charset_collate();
	
		$sql = "CREATE TABLE $table_name (
				id mediumint( 9 ) NOT NULL AUTO_INCREMENT ,
				aereo_min_lb double(10,2) DEFAULT NULL COMMENT 'Minimo en libras',
				aereo_cost_lb double(10,2) DEFAULT NULL COMMENT 'Costo en dolares por Libra',
				maritimo_min_pie double(10,2) DEFAULT NULL COMMENT 'Minimo en pies',
				maritimo_cost_pie double(10,2) DEFAULT NULL COMMENT 'Costo en dolares por pie',
				money_cost_usd double(10,2) DEFAULT NULL COMMENT 'Costo del dolar',
				money_unit tinytext NOT NULL COMMENT 'Unidad del costo Ej: Bs, USD, etc.',
				PRIMARY KEY ( `id` )) $charset_collate;";
	
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	
		add_option( 'calship_db_version', self::$bd_version );
	}
	
	private static function db_install_data() {
		global $wpdb;
		if ( self:: $table_calship == '')
			self::$table_calship = $wpdb->prefix . CALCULATIONSHIP_TABLE_NAME;
	
		$aereo_min_lb = 3.00;
		$aereo_cost_lb = 6.00;
		$maritimo_min_pie = 6.00;
		$maritimo_cost_pie = 6.00;
		$money_cost_usd = 180.00;
		$money_unit = 'Bs';
	
		$wpdb->insert(
				self::$table_calship,
				array(
						'aereo_min_lb' => $aereo_min_lb,
						'aereo_cost_lb' => $aereo_cost_lb,
						'maritimo_min_pie' => $maritimo_min_pie,
						'maritimo_cost_pie' => $maritimo_cost_pie,
						'money_cost_usd' => $money_cost_usd,
						'money_unit' => $money_unit,
				)
		);
	}
	
	public static function db_check_update() {
		global $jal_db_version;
		if ( get_site_option( 'calship_db_version' ) != self::$bd_version ) {
			Calculationship::db_install();
			Calculationship::db_install_data();
		}
	}
	
	private static function bail_on_activation( $message, $deactivate = true ) {
		?>
	<!doctype html>
	<html>
	<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<style>
	* {
		text-align: center;
		margin: 0;
		padding: 0;
		font-family: "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;
	}
	p {
		margin-top: 1em;
		font-size: 18px;
	}
	</style>
	<body>
	<p><?php echo esc_html( $message ); ?></p>
	</body>
	</html>
	<?php
			if ( $deactivate ) {
				$plugins = get_option( 'active_plugins' );
				$calculationship = plugin_basename( CALCULATIONSHIP__PLUGIN_DIR . 'calculationship.php' );
				$update  = false;
				foreach ( $plugins as $i => $plugin ) {
					if ( $plugin === $calculationship ) {
						$plugins[$i] = false;
						$update = true;
					}
				}
	
				if ( $update ) {
					update_option( 'active_plugins', array_filter( $plugins ) );
				}
			}
			exit;
		}
	
	public static function load_resources() {
		//http://code.tutsplus.com/articles/how-to-include-javascript-and-css-in-your-wordpress-themes-and-plugins--wp-24321
		//http://codex.wordpress.org/Function_Reference/wp_enqueue_script#jQuery_noConflict_wrappers
		//http://codex.wordpress.org/Function_Reference/wp_enqueue_script#Default_scripts_included_with_WordPress
		//https://codex.wordpress.org/Function_Reference/wp_register_style
		//http://codex.wordpress.org/Class_Reference/wpdb#Protect_Queries_Against_SQL_Injection_Attacks
		//http://codex.wordpress.org/Creating_Tables_with_Plugins
		//http://www.smashingmagazine.com/2011/03/08/ten-things-every-wordpress-plugin-developer-should-know/
		//https://codex.wordpress.org/Data_Validation
		//https://make.wordpress.org/core/handbook/coding-standards/css/
		//http://devotepress.com/wordpress-coding/how-to-properly-use-jquery-scripts-in-wordpress/#.VUJyy_l_Okp
		//http://codex.wordpress.org/Function_Reference/wp_enqueue_script#Link_a_Theme_Script_Which_Depends_on_jQuery
		global $wpdb;
		if ( self::$table_calship == '' )
			self::$table_calship = $wpdb->prefix . CALCULATIONSHIP_TABLE_NAME;
		
		$table_name = self::$table_calship;
		$calship = $wpdb->get_row("SELECT * FROM $table_name WHERE id='1' LIMIT 0, 1;", OBJECT, 0);
		
		wp_register_style( 'jquery-ui.css', '//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css', array( ), '1.11.4' );
		wp_register_style( 'front_calship.css', CALCULATIONSHIP_PLUGIN_URL . 'css/front_calship.css', array( 'jquery-ui.css' ), CALCULATIONSHIP_VERSION );
		wp_enqueue_style( 'front_calship.css');
		
		wp_register_script( 'front_calship.js', CALCULATIONSHIP_PLUGIN_URL . 'js/front_calship.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-dialog', 'jquery-ui-slider', 'jquery-ui-spinner', 'jquery-ui-selectmenu' ), CALCULATIONSHIP_VERSION );
		wp_enqueue_script( 'front_calship.js' );
		wp_localize_script('front_calship.js', 'calship', array(
				'money_unit'        => $calship->money_unit,
				'money_cost_usd'    => $calship->money_cost_usd,
				'aereo_min_lb'      => $calship->aereo_min_lb,
				'aereo_cost_lb'     => $calship->aereo_cost_lb,
				'maritimo_min_pie'  => $calship->maritimo_min_pie,
				'maritimo_cost_pie' => $calship->maritimo_cost_pie/*,
				'strings' => array(
						'Remove this URL' => __( 'Remove this URL' , 'akismet'),
						'Removing...'     => __( 'Removing...' , 'akismet'),
						'URL removed'     => __( 'URL removed' , 'akismet'),
						'(undo)'          => __( '(undo)' , 'akismet'),
						'Re-adding...'    => __( 'Re-adding...' , 'akismet'),
				)*/
		));

	}
	
	public static function load_shortcode() {
		include(CALCULATIONSHIP_PLUGIN_DIR . 'views/front.html');
	}
	
}