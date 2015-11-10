<?php

class SA_AdminLinks
{
	var $pages;
	
	function __construct()
	{
		// Main admin page
		$this-> pages[] = add_object_page(
			__("Silent Auction", 'silentauction'), 
			__("Silent Auction", 'silentauction'),
			'silent_auction_user', 
			'sa-admin-main', 
			array($this,'page_main'),
			'' //plugins_url( '/mce_plugins/formmanager.png', __FILE__ )
			);
		
		// Bidder management (CRUD + view statement)
		$this-> pages[] = add_submenu_page(
			'sa-admin-main',
			__("Bidders", 'silentauction'), 
			__("Bidders", 'silentauction'),
			'silent_auction_user',
			'sa-bidders',
			array($this,'page_bidders')
			);
		
		// Items management (CRUD + closeout + mark as paid)
		$this-> pages[] = add_submenu_page(
			'sa-admin-main',
			__("Auction Items", 'silentauction'), 
			__("Auction Items", 'silentauction'),
			'silent_auction_user',
			'sa-items',
			array($this,'page_items')
			);
			
		// Import
		$this-> pages[] = add_submenu_page(
			'sa-admin-main',
			__("Import", 'silentauction'), 
			__("Import", 'silentauction'),
			'silent_auction_admin',
			'sa-import',
			array($this,'page_import')
			);
			
		// Export
		$this-> pages[] = add_submenu_page(
			'sa-admin-main',
			__("Export", 'silentauction'), 
			__("Export", 'silentauction'),
			'silent_auction_admin',
			'sa-export',
			array($this,'page_export')
			);
			
		// Event management 
		$this-> pages[] = add_submenu_page(
			'sa-admin-main',
			__("Events", 'silentauction'), 
			__("Events", 'silentauction'),
			'silent_auction_superadmin',
			'sa-events',
			array($this,'page_events')
			);
			
		// Event detail
		$this-> pages[] = add_submenu_page(
			'sa-admin-main',
			__("Event Detail", 'silentauction'), 
			__("Event Detail", 'silentauction'),
			'silent_auction_superadmin',
			'sa-event-detail',
			array($this,'page_event_detail')
			);
		
		foreach ( $this-> pages as $page ){
			 add_action( 'admin_print_scripts-' . $page, array( $this, 'admin_scripts' ) );
		}
		
		foreach ( $this-> pages as $page ){
			add_action( 'admin_print_styles-' . $page, array( $this, 'admin_styles' ) );
		}
	}
	
	function page_main(){
		include 'pages/sa_page_admin_main.php';
	}
	
	function page_bidders(){
		include 'pages/sa_page_bidders.php';
	}
	
	function page_items(){
		include 'pages/sa_page_items.php';
	}

	function page_events(){
		include 'pages/sa_page_events.php';
	}
	
	function page_event_detail(){
		include 'pages/sa_page_event_detail.php';
	}
	
	function page_import(){
		include 'pages/sa_page_import.php';
	}
	
	function page_export(){
		include 'pages/sa_page_export.php';
	}
	
	function admin_scripts(){
		// wp_enqueue_script(
			 // 'jquery_mobile',
			 // plugins_url( '/js/jquery.mobile.custom.min.js' , __FILE__ ),
			 // array('jquery'),
			// '1.4.5'
			// );
	}

	function admin_styles(){
		// wp_register_style(
			 // 'jqm_theme',
			 // plugins_url( '/css/jquery.mobile.custom.theme.min.css' , __FILE__ ),
			 // '',
			 // '1.4.5'
			// );
		// wp_enqueue_style(
			 // 'jqm_theme',
			 // plugins_url( '/css/jquery.mobile.custom.theme.min.css' , __FILE__ ),
			 // '',
			 // '1.4.5'
			// );
			
		// wp_register_style(
			 // 'jqm_structure',
			 // plugins_url( '/css/jquery.mobile.custom.structure.min.css' , __FILE__ ),
			 // '',
			 // '1.4.5'
			// );
		// wp_enqueue_style(
			 // 'jqm_structure',
			 // plugins_url( '/css/jquery.mobile.custom.structure.min.css' , __FILE__ ),
			 // '',
			 // '1.4.5'
			// );
	}
	
	
}
?>
