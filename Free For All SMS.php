<?php
/*
Plugin Name: Free For All SMS
Description: A dedicated plugin for "MizbanPayamak" to send free SMS
Author: koorosh safe ashrafi
Version: 1.0
*/

/*
* installer
*/
function installer(){
	include(dirname(__FILE__).'/class.installer.php');
	
	$install = new Installer();
	$install->run();
}
 
register_activation_hook(__file__, 'installer');

/*
* create an administration menu item
*/
function test_plugin_setup_menu(){
	add_menu_page( 'Test Plugin Page', 'Test Plugin', 'manage_options', 'test-plugin', 'test_init' );
}
 
function test_init(){
	echo "<h1>Hello World!</h1>";
}

add_action('admin_menu', 'test_plugin_setup_menu');

/*
* create sms suggestion in all pages
*/
function insert_sms_box() {
	if (!is_page()) {
		include(dirname(__FILE__).'/class.smsQueries.php');
		$smsQueriesClass = new smsQueries();
		$setting = $smsQueriesClass->setting();

		include(dirname(__FILE__).'/class.free-sms-page.php');
		$sms = new Sms_page();
		$html = $sms->fixed_link($setting);

		echo $html;
	}
	return;
}

add_action('wp_footer', 'insert_sms_box');

/*
* create shortcodes
*/
function free_sms_page($atts){
	include(dirname(__FILE__).'/class.smsQueries.php');
	$smsQueriesClass = new smsQueries();
	$setting = $smsQueriesClass->setting();
	
	include(dirname(__FILE__).'/class.free-sms-page.php');
	$sms = new Sms_page();
	$html = $sms->get_phone($setting);

	return $html;
}
add_shortcode('free_for_all', 'free_sms_page');

/*
* add styles and scripts for shortcodes
*/
function assets() {
	wp_register_style('sms_style', plugins_url('style.css',__FILE__ ));
	wp_enqueue_style('sms_style');
	// wp_enqueue_script('jquery');
	wp_register_script( 'sms_script', plugins_url('script.js',__FILE__ ));
	wp_enqueue_script('sms_script');
}
add_action( 'init','assets');

/*
* define ajax api
*/
function myAjaxFunction(){  
	include(dirname(__FILE__).'/handle-ajax.php');
	exit();
}
add_action( 'wp_ajax_nopriv_myAjaxFunction', 'myAjaxFunction' );  
add_action( 'wp_ajax_myAjaxFunction', 'myAjaxFunction' );
