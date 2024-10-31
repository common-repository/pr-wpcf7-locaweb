<?php
/*
Plugin Name: PR WPCF7 Locaweb
Plugin URI: http://paulor.com.br
Description: Integração do Contact Form 7 com o sistema de Email Marketing da Locaweb.
Version: 1.0
Author: Paulo Iankoski
*/

DEFINE('prWPCF7LocawebOptions', 'PRWPCF7LocawebOptions');
require_once(dirname(__FILE__).'/pr-admin-functions.php');
require_once(dirname(__FILE__).'/pr-front-functions.php');

function prwpcf7locaweb_backend_scripts($hook) {
	//wp_enqueue_style( 'pr-wpcf7locaweb', plugin_dir_url(__FILE__).'/css/pr-wpcf7locaweb.css');
	//wp_enqueue_script( 'pr-wpcf7locaweb', plugin_dir_url(__FILE__).'/js/pr-wpcf7locaweb.js', array( 'jquery' ) );
}
add_action( 'admin_enqueue_scripts', 'prwpcf7locaweb_backend_scripts' );

if(!function_exists("PRWPCF7Locaweb_ap")){
	function PRWPCF7Locaweb_ap(){
		if(function_exists('add_submenu_page')):
			add_submenu_page( 'wpcf7', 'PR WPCF7 Locaweb', 'PR WPCF7 Locaweb', 'manage_options', 'edit.php?post_type=prwpcf7locaweb');
		endif;
	}
}
add_action('admin_menu', 'PRWPCF7Locaweb_ap');
