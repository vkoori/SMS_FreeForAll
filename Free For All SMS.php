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
		include(dirname(__FILE__).'/class.setting.php');

		$settingClass = new setting();
		$setting = $settingClass->get();

		echo '<div id="free_for_all_click_me_text_'.$setting->theme.'">
			<div id="free_for_all_click_me_close_'.$setting->theme.'" onclick="free_for_all_click_me_close(\'free_for_all_click_me_text_'.$setting->theme.'\');"></div>
			اگر میخوای همین الان پیامک رایگان ارسال کنی، کلیک کن
		</div>
		<div id="free_for_all_click_me_'.$setting->theme.'">
			<a href="'.get_permalink($setting->pageid).'" target="_blank" title="ارسال رایگان پیامک">
				<img width="50" height="50" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZlcnNpb249IjEuMSIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbG5zOnN2Z2pzPSJodHRwOi8vc3ZnanMuY29tL3N2Z2pzIiB3aWR0aD0iNTEyIiBoZWlnaHQ9IjUxMiIgeD0iMCIgeT0iMCIgdmlld0JveD0iMCAwIDUxMi4wNTMgNTEyLjA1MyIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNTEyIDUxMiIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgY2xhc3M9IiI+PGcgdHJhbnNmb3JtPSJtYXRyaXgoLTEsMCwwLDEsNTEyLjA1Mjg0MzA5Mzg3MjEsMCkiPjxnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZD0ibS4wMTMgMzY4LjY1M2MuMjcgNi4yNCA0LjM2IDExLjY2IDEwLjI5IDEzLjYxbDg5LjgyIDI5LjY0IDI5LjY0IDg5LjgyYzEuOTUgNS45MyA3LjM3IDEwLjAyIDEzLjYxIDEwLjI5IDYuMDczLjI4OSAxMS45MzctMy4yNzIgMTQuNDMtOS4xbDk2LTIyNWM1LjI4Ni0xMi40MjQtNy4yNy0yNC45NzUtMTkuNjktMTkuNjlsLTIyNSA5NmMtNS43NCAyLjQ1LTkuMzYgOC4yLTkuMSAxNC40M3oiIGZpbGw9IiM0ZDU3ODgiIGRhdGEtb3JpZ2luYWw9IiM0ZDU3ODgiIHN0eWxlPSIiIGNsYXNzPSIiPjwvcGF0aD48cGF0aCBkPSJtMTAwLjEyMyA0MTEuOTAzIDI5LjY0IDg5LjgyYzEuOTUgNS45MyA3LjM3IDEwLjAyIDEzLjYxIDEwLjI5IDYuMDczLjI4OSAxMS45MzctMy4yNzIgMTQuNDMtOS4xbDk2LTIyNWMyLjQtNS42NCAxLjE0LTEyLjE3LTMuMTktMTYuNXoiIGZpbGw9IiMzMTNhNmIiIGRhdGEtb3JpZ2luYWw9IiMzMTNhNmIiIHN0eWxlPSIiIGNsYXNzPSIiPjwvcGF0aD48cGF0aCBkPSJtOTcuMDAzIDI1Ni4wMjRjMCA4LjI4IDYuNzIgMTUgMTUgMTVzMTUtNi43MiAxNS0xNWMwLTcxLjEzIDU3Ljg3LTEyOSAxMjktMTI5IDcxLjEyNyAwIDEyOSA1Ny44NzggMTI5IDEyOSAwIDcxLjEzLTU3Ljg3IDEyOS0xMjkgMTI5LTguMjggMC0xNSA2LjcyLTE1IDE1czYuNzIgMTUgMTUgMTVjODcuNjcgMCAxNTktNzEuMzMgMTU5LTE1OSAwLTg3LjY2OS03MS4zMTctMTU5LTE1OS0xNTktODcuNjcgMC0xNTkgNzEuMzMtMTU5IDE1OXoiIGZpbGw9IiNmM2Q2NTIiIGRhdGEtb3JpZ2luYWw9IiNmM2Q2NTIiIHN0eWxlPSIiIGNsYXNzPSIiPjwvcGF0aD48cGF0aCBkPSJtMjQxLjAwMyA0MDAuMDI0YzAgOC4yOCA2LjcyIDE1IDE1IDE1IDg3LjY3IDAgMTU5LTcxLjMzIDE1OS0xNTkgMC00My44NC0xNy44My04My41OC00Ni42Mi0xMTIuMzhsLTIxLjIxIDIxLjIxYzIzLjM2IDIzLjM1IDM3LjgzIDU1LjYxIDM3LjgzIDkxLjE3IDAgNzEuMTMtNTcuODcgMTI5LTEyOSAxMjktOC4yOCAwLTE1IDYuNzItMTUgMTV6IiBmaWxsPSIjZTliYzNlIiBkYXRhLW9yaWdpbmFsPSIjZTliYzNlIiBzdHlsZT0iIiBjbGFzcz0iIj48L3BhdGg+PHBhdGggZD0ibS4wMDMgMjU2LjAyNGMwIDguMjggNi43MiAxNSAxNSAxNXMxNS02LjcyIDE1LTE1YzAtMTI0LjYyIDEwMS4zOC0yMjYgMjI2LTIyNiAxMjQuNjE3IDAgMjI2IDEwMS4zNzYgMjI2IDIyNiAwIDEyNC42Mi0xMDEuMzggMjI2LTIyNiAyMjYtOC4yOCAwLTE1IDYuNzItMTUgMTVzNi43MiAxNSAxNSAxNWMxNDAuOTY2IDAgMjU2LTExNS4wNTYgMjU2LTI1NiAwLTE0MC45NjYtMTE1LjA1Ni0yNTYtMjU2LTI1Ni0xNDAuOTY2IDAtMjU2IDExNS4wNTUtMjU2IDI1NnoiIGZpbGw9IiNlMjdhNGUiIGRhdGEtb3JpZ2luYWw9IiNlMjdhNGUiIHN0eWxlPSIiIGNsYXNzPSIiPjwvcGF0aD48cGF0aCBkPSJtMjQxLjAwMyA0OTcuMDI0YzAgOC4yOCA2LjcyIDE1IDE1IDE1IDY4LjExIDAgMTMyLjMzLTI2LjcgMTgwLjgxLTc1LjE5IDEwMC4yOTctMTAwLjI3NyAxMDAuMzQzLTI2MS4yOTcgMC0zNjEuNjJsLTIxLjA4IDIxLjA4YzQwLjkzIDQwLjkyIDY2LjI3IDk3LjQyIDY2LjI3IDE1OS43MyAwIDEyNC42Mi0xMDEuMzggMjI2LTIyNiAyMjYtOC4yOCAwLTE1IDYuNzItMTUgMTV6IiBmaWxsPSIjZGM0NTViIiBkYXRhLW9yaWdpbmFsPSIjZGM0NTViIiBzdHlsZT0iIiBjbGFzcz0iIj48L3BhdGg+PC9nPjwvZz48L3N2Zz4=" />
			</a>
		</div>';
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
function assets() {
	wp_register_style('sms_style', plugins_url('style.css',__FILE__ ));
	wp_enqueue_style('sms_style');
	// wp_enqueue_script('jquery');
	wp_register_script( 'sms_script', plugins_url('script.js',__FILE__ ));
	wp_enqueue_script('sms_script');
}
add_action( 'init','assets');