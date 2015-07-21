<?php

class SA_Table
{
	var $name;
	
	function __construct( $name )
	{
		global $wpdb;
		$this->name = $wpdb-> prefix . 'sa_' . $name;
	}
	
	function uninstall(){
		global $wpdb;
		$sql = "DROP TABLE IF EXISTS `{$this->name}`;";
		$wpdb->query( $sql );
	}
	
	protected function _createTable( $sql ){
		global $wpdb;
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		$charset_collate = $this->_getCharsetCollation();
		$sql = $sql . " " . $charset_collate . ";";
		dbDelta( $sql );
	}
	
	protected function _getCharsetCollation(){
		global $wpdb;
		
		$charset_collate = "";
		
		//establish the current charset / collation
		if (!empty($wpdb->charset))
			$charset_collate = "CHARACTER SET ".$wpdb->charset;
		if (!empty($wpdb->collate))
			$charset_collate.= " COLLATE ".$wpdb->collate;
		
		return $charset_collate;
	}
}
?>