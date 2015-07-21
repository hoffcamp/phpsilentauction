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
			'' //plugins_url( '/mce_plugins/formmanager.png', __FILE__ )
			);
	}
	
	function main_page(){
		include 'pages/sa_admin_main.php';
	}
}
?>
