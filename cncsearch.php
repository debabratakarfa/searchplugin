<?php
/**
 * @package CNC_Search
 * @version 1.0
 */
/*
Plugin Name: CNC Search Form
Plugin URI: http://appealingstudio.com/wordpress/plugins/cncsearch/
Description: This Plugin is develop for Care and Care Insurance Company. To search providers, from their list.
Author: Appealing Studio, Inc.
Version: 1.0
Author URI: http://appealingstudio.com
*/

// Load Framework
//define('__ROOT__', dirname(dirname(__FILE__))); 
//require_once( 'titan-framework-checker.php' );


include_once dirname( __FILE__ ) . '/includes/install.php';
register_activation_hook( __FILE__, 'cncsearch_install' );

include_once dirname( __FILE__ ) . '/includes/shortcode.php';
include_once dirname( __FILE__ ) . '/includes/result_shortcode.php';


    function cncsearch_scripts_basic()
	{
	    // Register the script like this for a plugin:
	    wp_register_script( 'custom-script', plugins_url( '/js/addon.js', __FILE__ ), array('json2', 'jquery'), '1.0.0', false );
	    wp_register_script( 'getposition-script', plugins_url( '/js/geoPosition.js', __FILE__ ), array(), '1.0.0', true );
    	wp_register_script( 'geopositionsimulator-script', plugins_url( '/js/geoPositionSimulator.js', __FILE__ ), array(), '1.0.0', true );
	    wp_register_script( 'google-map-script', '//maps.google.com/maps/api/js?sensor=false', array(), null, true );
	    wp_register_script( 'geoplugin-script', '//www.geoplugin.net/javascript.gp', array(), null, false );
	    wp_register_script( 'bootstrapjs', plugins_url( '/js/bootstrap.js', __FILE__ ), array(), null, fasle );
	    wp_register_style( 'cncsearch', plugins_url( '/css/default.css', __FILE__ ), array(), '1.0.0', false );
	    wp_register_style( 'cncjqueryui', plugins_url( '/css/bootstrap.css', __FILE__ ), array(), '1.0.0', false );
	    wp_register_style( 'cnc_font_awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css', array(), '1.0.0', false );
			    
	    // For either a plugin or a theme, you can then enqueue the script:
	    wp_enqueue_script( 'custom-script' );
	    wp_enqueue_script( 'getposition-script' );
	    wp_enqueue_script( 'geopositionsimulator-scriptt' );
	    wp_enqueue_script( 'google-map-script' );
		wp_enqueue_script( 'geoplugin-script' );
		wp_enqueue_script( 'bootstrapjs' );
		wp_enqueue_style( 'cncsearch' );
		wp_enqueue_style( 'cncjqueryui' );
		wp_enqueue_style( 'cnc_font_awesome' );
}
add_action( 'wp_enqueue_scripts', 'cncsearch_scripts_basic' );

require_once plugin_dir_path( __FILE__ ) . 'titan-framework/titan-framework-embedder.php';

add_action( 'tf_create_options', 'mytheme_create_options' );
function mytheme_create_options() {
    // We create all our options here

  
$titan = TitanFramework::getInstance( 'my-theme' );

$adminPanel = $titan->createAdminPanel( array(

    'name' => 'CNC Panel',
    'position' => '10',
    'icon' => 'dashicons-editor-unlink',

) );

	//$lPanel = $layoutPanel->cncsearch_update_location_function();

	$myGTabs = $adminPanel->createTab( array(

	    'name' => 'General Settings',

	) );


 	$myGTabs->createOption( array(

	'name' => 'Select Result Display Page',

	'id' => 'cnc_select_rd_page',

	'type' => 'select-pages',

	'desc' => 'Select the page, where you want to display the search result!'

	) );

    $myGTabs->createOption( array(

        'type' => 'save'

    ) );

}

add_action('admin_menu', 'register_my_custom_submenu_page');

function register_my_custom_submenu_page() {
  add_submenu_page( 'cnc-panel', 'CNC Option Panel', 'CNC Option', 'manage_options', 'cnc-addon', 'cnc_addon_callback' );
  add_submenu_page( 'cnc-panel', 'Update Location of Address', 'Update Location', 'manage_options', 'cnc-update-location', 'cnc_update_location_callback' );
 add_submenu_page( 'cnc-panel', 'Update Directory File', 'Update Directory', 'manage_options', 'cnc-update-directory', 'cnc_update_directory_callback' );
}

function cnc_addon_callback() {
	include_once dirname( __FILE__ ) . '/cncsearch_options.php';
}

function cnc_update_location_callback() {
	include_once dirname( __FILE__ ) . '/cncsearch_update_location.php';	
}

function cnc_update_directory_callback() {
	include_once dirname( __FILE__ ) . '/cncsearch_update_directory.php';
}