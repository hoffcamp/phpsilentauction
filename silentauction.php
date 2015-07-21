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
	
require 'sa_capabilities.php';
require 'sa_adminlinks.php';

global $SA_Capabilities;
global $SA_AdminLinks;

add_action( 'init', 'sa_userInit' );
function sa_userInit() {
	global $SA_Capabilities;
	
	$SA_Capabilities = new SA_Capabilities();
}

add_action( 'admin_menu', 'sa_adminMenu' );
function sa_adminMenu() {
	global $SA_AdminLinks;
	
	$SA_AdminLinks = new SA_AdminLinks();
}