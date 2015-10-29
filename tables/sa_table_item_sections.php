<?php

class SA_ItemSectionsTable extends SA_Table
{
	function install(){
		$this->_createTable(
			"CREATE TABLE `".$this->name."` ( 
			`ID` INT(11) NOT NULL AUTO_INCREMENT,
			`eventID` INT(11) DEFAULT '0' NOT NULL ,
			`title` VARCHAR( 255 ) DEFAULT '' NOT NULL ,			
			PRIMARY KEY ( `ID` )
			)" );
	}
	
	// [ ID ]
	function add( $eventID, $title ){
		global $wpdb; 
		$wpdb-> query(
		$wpdb-> prepare(
			"INSERT INTO `{$this->name}` (`eventID`, `title`) VALUES ('%s', '%s')",
			$eventID, $title ) );
		$results = $wpdb->get_row( 'SELECT LAST_INSERT_ID() as `ID`;', ARRAY_A );
		return $results[ 'ID' ];
	}
	
	// true
	function update( $ID, $title ){
		global $wpdb;
		$result = $wpdb-> query(
		$wpdb-> prepare( 
			"UPDATE `{$this->name}` SET `title` = '%s' WHERE `ID` = %d;",
			$title, $ID ) );	
		if ( $result === false ) { return $result; }
		return true;
	}
	
	// [ [*], ... ]
	function getAll( $eventID, $ascending = true ){
		global $wpdb;
		if ( $ascending !== true ){
			$sort = "DESC";
		} else {
			$sort = "";
		}
		return $wpdb->get_results(
				$wpdb->prepare( "SELECT * FROM `{$this->name}` WHERE `eventID` = '%d' ORDER BY `ID` {$sort}", $eventID ), ARRAY_A );
	}
	
	

}

?>