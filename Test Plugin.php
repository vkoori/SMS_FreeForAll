<?php
/*
Plugin Name: Test plugin
Description: A test plugin to demonstrate wordpress functionality
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
	if (is_single()) {
		echo 'sadasd';
	}
	return;
}

add_action('wp_footer', 'insert_sms_box');

/*
* create shortcodes
*/
function free_sms_page($atts){
	// global $content;
	$attr = shortcode_atts( array(
		'count' => 3,
		'category' => "مبل کلاسیک",
		'name' => "",
		'exclude' => "",
	), $atts );

	include(dirname(__FILE__).'/class.free-sms-page.php');

	$sms = new Sms_page();
	$html = $sms->free_html();

	return $html;
}
add_shortcode('free_for_all', 'free_sms_page');

/*
* add styles and scripts for shortcodes
*/
// function assets() {
// 	wp_enqueue_style( 'dashicons' );
// 	wp_register_style('suggestion', plugins_url('style.css',__FILE__ ));
// 	wp_enqueue_style('suggestion');
// 	wp_enqueue_script('jquery');
// 	wp_register_script( 'suggestion', plugins_url('script.js',__FILE__ ));
// 	wp_enqueue_script('suggestion');
// }
// add_action( 'init','assets');