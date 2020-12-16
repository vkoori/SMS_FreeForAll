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

		if ($_POST['freeSmsTimeCycle']=="")
			$freeSmsTime = NULL;
		else
			$freeSmsTime = $_POST['freeSmsTimeCycle'].'|'.$_POST['freeSmsTime'];

		$data = array(
			'pageid' => $_POST['pageid'],
			'background' => $_POST['background'],
			'foreground' => $_POST['foreground'],
			'freeSmsCount' => $_POST['freeSmsCount'],
			'freeSmsTime' => $freeSmsTime,
			'user_api' => $_POST['user_api'],
			'pass_api' => $_POST['pass_api'],
			'phone_number' => $_POST['phone_number'],
			'api_number' => $_POST['api_number'],
			'other_texts' => serialize(
				array(
					'signature' => $_POST['signature'],
					'profile' => (isset($_POST["profile"])) ? $_POST["profile"] : array(),
					'gift_text' => $_POST['gift_text'],
					
				)
			)
		);
		$smsQueriesClass->update_setting($data);
	}

	$setting = $smsQueriesClass->setting();

	$args = array(
		'sort_order' => 'asc',
		'sort_column' => 'post_title',
		'hierarchical' => 1,
		'exclude' => '',
		'include' => '',
		'meta_key' => '',
		'meta_value' => '',
		'authors' => '',
		'child_of' => 0,
		'parent' => -1,
		'exclude_tree' => '',
		'number' => '',
		'offset' => 0,
		'post_type' => 'page',
		'post_status' => 'publish'
	); 
	$pages = get_pages($args); // get all pages based on supplied args

	include(dirname(__FILE__).'/class.free-sms-page.php');
	$sms = new Sms_page();
	$html = $sms->admin_sms_setting($setting, $pages);

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
		// include(dirname(__FILE__).'/class.smsQueries.php');
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
* create sms suggestion in all pages
*/
function php_styles() {
	include(dirname(__FILE__).'/class.smsQueries.php');
	$smsQueriesClass = new smsQueries();
	$setting = $smsQueriesClass->setting();
	
	echo '<style>
	.free_for_all_color_default{
		background-color: '.$setting->background.'!important;
		color: '.$setting->foreground.'!important;
	}
	.free_for_all_border_default{
		border: 1px solid '.$setting->background.'!important;
	}
	#free_for_all_click_me_text_default:after {
		border-top: 15px solid '.$setting->background.'!important;
	}
	#free_for_all_step_box .free_for_all_input_default:focus,
	#free_for_all_step_box .free_for_all_input_default:active{
		border: solid 1px '.$setting->background.'!important;
	}
	.swipe-overlay-out{
		color: '.$setting->foreground.'!important;
		background-color: '.$setting->background.'!important;
		box-shadow: inset 0 0 0 1px '.$setting->foreground.', 0 0 0 0 '.$setting->background.'!important;

	}
	.swipe-overlay-out::after{
		background: '.$setting->foreground.'!important;
	}
	.swipe-overlay-out:hover{
		box-shadow: inset 0 0 0 0 '.$setting->foreground.', 3px 3px 4px -1px '.$setting->background.'!important;
	}
	</style>';

	return;
}

add_action('wp_head', 'php_styles');

/*
* create shortcodes
*/
function free_sms_page($attr){
	$attr = shortcode_atts( array(
		'text' => 'ارسال پیامک رایگان',
	), $attr );

	// if (!class_exists('smsQueries'))
	// 	include(dirname(__FILE__).'/class.smsQueries.php');
	// $smsQueriesClass = new smsQueries();
	// $setting = $smsQueriesClass->setting();
	
	include(dirname(__FILE__).'/class.free-sms-page.php');
	$sms = new Sms_page();
	// $html = $sms->get_popup($setting, $attr);
	$html = $sms->get_popup($attr);

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
function popupForm(){
	include(dirname(__FILE__).'/popupForm.php');
	exit();
}
add_action( 'wp_ajax_nopriv_popupForm', 'popupForm' );  
add_action( 'wp_ajax_popupForm', 'popupForm' );

function updateForm() {
	include(dirname(__FILE__).'/handle-ajax.php');
	exit();
}
add_action( 'wp_ajax_nopriv_updateForm', 'updateForm' );  
add_action( 'wp_ajax_updateForm', 'updateForm' );

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