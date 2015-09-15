<?php
/*
Plugin Name: Silent Auction
Plugin URI: 
Description: Manage silent auctions. 
Version: 0.1
Author: Campbell Hoffman
Author URI: http://www.campbellhoffman.com/
Text Domain: silentauction
License: GPL2

	Copyright 2015 Campbell Hoffman

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; version 2 of the License (GPL v2) only.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Internationalization
load_plugin_textdomain(
	'silentauction', 
	false, 
	dirname( plugin_basename( __FILE__ ) ) . '/languages/' 
	);
	
error_reporting( E_ALL );
	
require 'sa_capabilities.php';
require 'sa_adminlinks.php';
require 'sa_tables.php';
require 'sa_options.php';
require 'sa_crud.php';

require 'pages/sa_nav.php';
require 'pages/includes/sa_page.php';
require 'pages/bidders/sa_bidders_form_summary.php';
require 'pages/items/sa_items_form_close.php';
require 'pages/items/sa_items_form_reopen.php';
require 'pages/import/sa_import_form_items_upload.php';
require 'pages/import/sa_import_form_bidders_upload.php';
require 'PHPExcel-1.8/Classes/PHPExcel.php';

global $SA_Capabilities;
global $SA_AdminLinks;
global $SA_Tables;
global $SA_Options;

// install
function sa_install(){
	global $SA_Tables;
	global $SA_Options;
	
	$SA_Tables = new SA_Tables();
	$SA_Tables->install();
	
	$SA_Options = new SA_Options();
	$SA_Options->install();
}
register_activation_hook( __FILE__, 'sa_install' );

// uninstall
function sa_uninstall(){
	global $SA_Tables;
	global $SA_Options;
	
	$SA_Tables = new SA_Tables();
	$SA_Tables->uninstall();
	
	$SA_Options = new SA_Options();
	$SA_Options->uninstall();
}
register_uninstall_hook( __FILE__, 'sa_uninstall' );

function sa_forcelogin_redirect() {
	return site_url( '/wp-admin/admin.php?page=sa-admin-main' );
}
add_filter('v_forcelogin_redirect', 'sa_forcelogin_redirect', 10, 1);

// user init
add_action( 'init', 'sa_userInit' );
function sa_userInit() {
	global $SA_Capabilities;
	global $SA_Tables;
	global $SA_Options;
	
	$SA_Capabilities = new SA_Capabilities();
	$SA_Tables = new SA_Tables();
	$SA_Options = new SA_Options();
	
	if(!session_id()) {
        session_start();
    }
}

// admin init
add_action( 'admin_menu', 'sa_adminMenu' );
function sa_adminMenu() {
	global $SA_AdminLinks;
	
	$SA_AdminLinks = new SA_AdminLinks();
}

// sessions
add_action('wp_logout', 'sa_end_session');
add_action('wp_login', 'sa_end_session');

function sa_end_session() {
    session_destroy();
}