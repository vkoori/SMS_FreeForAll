<?php
/*
Plugin Name: Test plugin
Description: A test plugin to demonstrate wordpress functionality
Author: Simon Lissack
Version: 0.1
*/

add_action('admin_menu', 'test_plugin_setup_menu');
 
function test_plugin_setup_menu(){
    add_menu_page( 'Test Plugin Page', 'Test Plugin', 'manage_options', 'test-plugin', 'test_init' );
}
 
function test_init(){
    echo "<h1>Hello World!</h1>";
}


function installer(){
    include('class.installer.php');
    
    $install = new Installer();
    $install->run();
}
register_activation_hook(__file__, 'installer');