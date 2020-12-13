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
function sms_plugin_setup_menu(){
	add_menu_page('پیامک رایگان', 'پیامک رایگان', 'manage_options', 'free-sms-setting', 'admin_sms_setting', 'dashicons-welcome-write-blog');
	add_submenu_page( 'free-sms-setting', 'تنظیمات', 'تنظیمات', 'manage_options', 'free-sms-setting', 'admin_sms_setting');
	add_submenu_page( 'free-sms-setting', 'متن پیامک', 'متن پیامک', 'manage_options', 'free-sms-text', 'admin_sms_text');
}
 
function admin_sms_setting(){
	include(dirname(__FILE__).'/class.smsQueries.php');
	$smsQueriesClass = new smsQueries();

	if (sizeof($_POST) > 0) {
		$data = array(
			'pageid' => $_POST['pageid'],
			'theme' => $_POST['theme'],
			'freeSmsCount' => $_POST['freeSmsCount'],
			'freeSmsTime' => ($_POST['freeSmsTime']=="") ? NULL : $_POST['freeSmsTime'],
			'user_api' => $_POST['user_api'],
			'pass_api' => $_POST['pass_api']
		);
		$smsQueriesClass->update_setting($data);
	}

	$setting = $smsQueriesClass->setting();

	include(dirname(__FILE__).'/class.free-sms-page.php');
	$sms = new Sms_page();
	$html = $sms->admin_sms_setting($setting);

	echo $html;
}

function admin_sms_text () {
	include(dirname(__FILE__).'/class.smsQueries.php');
	$smsQueriesClass = new smsQueries();

	if (sizeof($_FILES) > 0) {
		include(dirname(__FILE__).'/class.upload-xlsx.php');
		$upload = new Upload();
		$upload = $upload->uploadfile($_FILES['xlsx']);

		if (isset($upload["error"])) {
			echo $upload["error"];
			exit();
		}

		include(dirname(__FILE__).'/SimpleXLSX.php');
		if ( $xlsx = SimpleXLSX::parse($upload["success"]) ) {
			$rows = $xlsx->rows();
			$subjects = array();
			$texts = array();
			foreach ($rows as $row) {
				if (!in_array("('".$row[1]."')", $subjects))
					array_push($subjects, "('".$row[1]."')");
				
				array_push($texts, "(".(array_search("('".$row[1]."')", $subjects)+1).",'".$row[0]."')");
			}
			$subjects = implode(',', $subjects);
			$smsQueriesClass->overwite_subjects($subjects);

			$texts = implode(',', $texts);
			$smsQueriesClass->overwite_quotes($texts);
		} else {
			echo SimpleXLSX::parseError();
		}


	}

	$texts = $smsQueriesClass->all_texts();

	include(dirname(__FILE__).'/class.free-sms-page.php');
	$sms = new Sms_page();
	$html = $sms->admin_sms_text();
	echo $html;
	$html = $sms->admin_sms_text2($texts);
	echo $html;

}

add_action('admin_menu', 'sms_plugin_setup_menu');

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

function textsOfSubject() {
	header('Content-type: application/json');

	$sid = $_GET["sid"];
	include(dirname(__FILE__).'/class.smsQueries.php');
	$smsQueriesClass = new smsQueries();
	$texts = $smsQueriesClass->texts_with_subjectid($sid);

	$result = array(
		'texts' => $texts
	);
	echo json_encode($result);
	exit();
}
add_action( 'wp_ajax_nopriv_textsOfSubject', 'textsOfSubject' );  
add_action( 'wp_ajax_textsOfSubject', 'textsOfSubject' );