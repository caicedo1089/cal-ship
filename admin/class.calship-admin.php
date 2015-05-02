<?php
class CalShip_Admin {
	const TABLE_CALSHIP = 'calship';
	
	private static $initiated = false;
	private static $table_calship = '';
	
	public static function init() {
		global $wpdb;
		self::$table_calship = $wpdb->prefix . self::TABLE_CALSHIP;
	
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}
	
	/**
	 * Initializes WordPress hooks
	 */
	private static function init_hooks() {
		self::$initiated = true;
		
		add_action( 'admin_init', array( 'CalShip_Admin', 'admin_init' ) );
		add_action( 'admin_menu', array( 'CalShip_Admin', 'admin_menu' ), 5 );
		add_action( 'admin_enqueue_scripts', array( 'CalShip_Admin', 'load_resources' ) );
	}
	
	public static function admin_init() {
	}
	
	public static function admin_menu() {
			self::load_menu();
	}
	
	public static function load_menu() {
		add_options_page( __('Calculation Ship', 'calculationship'), __('Calculation Ship', 'calculationship'), 'manage_options', 'calculationship-key-config', array( 'Calculationship_Admin', 'display_page' ) );
	}
	
	public static function display_page() {
			self::display_configuration_page();
	}
	
	public static function display_configuration_page(){
		global $wpdb;
		if ( self::$table_calship == '' )
			self::$table_calship = $wpdb->prefix . CALSHIP_TABLE_NAME;
		
		$table_name = self::$table_calship;
		
		$data = array();
		
		if(isset($_POST['aereo_min_lb']))
			$data['aereo_min_lb'] = $_POST['aereo_min_lb'];
		if(isset($_POST['aereo_cost_lb']))
			$data['aereo_cost_lb'] = $_POST['aereo_cost_lb'];
		if(isset($_POST['maritimo_min_pie']))
			$data['maritimo_min_pie'] = $_POST['maritimo_min_pie'];
		if(isset($_POST['maritimo_cost_pie']))
			$data['maritimo_cost_pie'] = $_POST['maritimo_cost_pie'];
		if(isset($_POST['money_cost_usd']))
			$data['money_cost_usd'] = $_POST['money_cost_usd'];
		if(isset($_POST['money_unit']))
			$data['money_unit'] = $_POST['money_unit'];
		
		if ( isset( $_POST ))
			$wpdb->update( self::$table_calship, $data,	array( 'id' => 1 ) );
		
		$sql = "SELECT * FROM $table_name WHERE id=1;";
		$result = $wpdb->get_row($sql, OBJECT, 0);
		
		$aereo_min_lb = $result->aereo_min_lb;
		$aereo_cost_lb = $result->aereo_cost_lb;
		$maritimo_min_pie = $result->maritimo_min_pie;
		$maritimo_cost_pie = $result->maritimo_cost_pie;
		$money_cost_usd = $result->money_cost_usd;
		$money_unit = $result->money_unit;
	
		include(CALSHIP_PLUGIN_DIR . 'views/panel.html');
	}
	
	public static function load_resources() {
		wp_register_style( 'jquery-ui.css', '//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css', array( ), '1.11.4' );
		wp_register_style( 'admin_calship.css', CALSHIP_PLUGIN_URL . 'css/admin_calship.css', array( 'jquery-ui.css' ), CALSHIP_VERSION );
		wp_enqueue_style( 'admin_calship.css');
		
		wp_register_script( 'admin_calship.js', CALSHIP_PLUGIN_URL . 'js/admin_calship.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-slider', 'jquery-ui-spinner', 'jquery-ui-selectmenu' ), CALSHIP_VERSION );
		wp_enqueue_script( 'admin_calship.js' );
		
	}
	
}