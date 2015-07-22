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
	
	// true on success
	function remove( $ID ){
		global $wpdb;
		$result = $wpdb-> query(
		$wpdb-> prepare( 
			"DELETE FROM `{$this->name}` WHERE `ID` = %d;",
			$ID ) );	
		if ( $result === false ) { return $result; }
		return true;
	}
	
	// [ * ]
	function getByID( $ID ){
		global $wpdb;
		return $wpdb->get_row(
			$wpdb->prepare( "SELECT * FROM `{$this->name}` WHERE `ID` = %d;",
			$ID ), ARRAY_A );
	}
	
	// [ [*], ... ]
	function _getAll( $sortCol, $ascending = true ){
		global $wpdb;
		
		if ( $ascending !== true ){
			$sort = "DESC";
		} else {
			$sort = "";
		}
		return $wpdb->get_results( "SELECT * FROM `{$this->name}` ORDER BY `{$sortCol}` {$sort}", ARRAY_A );
	}
	
}
?>