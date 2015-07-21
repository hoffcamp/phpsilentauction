<?php
class SA_AdminLinks
{
	var $pages;
	
	function __construct()
	{
		// Main admin page
		$this-> pages[] = add_object_page(
			__("Auction", 'silentauction'), 
			__("Auction", 'silentauction'),
			'silent_auction_superadmin', 
			'sa-admin-main', 
			array($this,'main_page'),
			''//plugins_url( '/mce_plugins/formmanager.png', __FILE__ )
			);
	}
	
	function main_page(){
		include 'pages/sa_admin_main.php';
	}
}


/*
add_action( 'admin_menu', 'fm_setupAdminMenu' );
function fm_setupAdminMenu() {
	global $fmdb;
	
	$pages[] = add_object_page(
		__("Forms", 'wordpress-form-manager'), 
		__("Forms", 'wordpress-form-manager'),
		apply_filters( 'fm_main_capability', 'manage_options' ), 
		'fm-admin-main', 
		'fm_showMainPage',
		plugins_url( '/mce_plugins/formmanager.png', __FILE__ )
		);
		
	$pages[] = add_submenu_page(
		'fm-admin-main',
		__("Edit", 'wordpress-form-manager'),
		__("Edit", 'wordpress-form-manager'),
		apply_filters( 'fm_main_capability', 'manage_options' ),
		'fm-edit-form',
		'fm_showEditPage'
		);
		
	$pages[] = add_submenu_page(
		'fm-admin-main',
		__("Settings", 'wordpress-form-manager'),
		__("Settings", 'wordpress-form-manager'),
		apply_filters( 'fm_settings_capability', 'manage_options' ),
		'fm-global-settings',
		'fm_showSettingsPage'
		);
		
	$pages[] = add_submenu_page(
		'fm-admin-main',
		__("Advanced Settings", 'wordpress-form-manager'),
		__("Advanced Settings", 'wordpress-form-manager'),
		apply_filters( 'fm_settings_advanced_capability', 'manage_options' ),
		'fm-global-settings-advanced',
		'fm_showSettingsAdvancedPage'
		);
	
	foreach ( $pages as $page ) {
		add_action( 'admin_head-' . $page, 'fm_adminHeadPluginOnly' );
	}
	
	$pluginName = plugin_basename( __FILE__ );
	add_filter( 'plugin_action_links_' . $pluginName, 'fm_pluginActions' );
}

function fm_pluginActions( $links ) { 
	$settings_link = 
		'<a href="' . get_admin_url( null, 'admin.php' ) . "?page=fm-global-settings".'">' .
		__('Settings', 'wordpress-form-manager') . '</a>';
	array_unshift( $links, $settings_link );
	
	return $links;
}	
*/
?>
