<?php

class SA_ItemSectionsTable extends SA_Table
{
	function install(){
		$this->_createTable(
			"CREATE TABLE `".$this->name."` ( 
			`ID` INT(11) NOT NULL AUTO_INCREMENT,
			`eventID` INT(11) DEFAULT '0' NOT NULL ,
			`title` VARCHAR( 255 ) DEFAULT '' NOT NULL ,
			`typeID` INT(11) DEFAULT '0' NOT NULL ,
			PRIMARY KEY ( `ID` )
			)" );
	}
	
	// [ ID ]
	function add( $eventID, $title, $typeID ){
		global $wpdb; 
		$wpdb-> query(
		$wpdb-> prepare(
			"INSERT INTO `{$this->name}` (`eventID`, `title`, `typeID`) VALUES ('%s', '%s', '%s')",
			$eventID, $title, $typeID ) );
		$results = $wpdb->get_row( 'SELECT LAST_INSERT_ID() as `ID`;', ARRAY_A );
		return $results[ 'ID' ];
	}
	
	// true
	function update( $ID, $title, $typeID ){
		global $wpdb;
		$result = $wpdb-> query(
		$wpdb-> prepare( 
			"UPDATE `{$this->name}` SET `title` = '%s', `typeID` = %d WHERE `ID` = %d;",
			$title, $typeID, $ID ) );	
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