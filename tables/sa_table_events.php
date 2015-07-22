<?php

class SA_EventsTable extends SA_Table
{
	function install(){
		$this->_createTable(
			"CREATE TABLE `".$this->name."` ( 
			`ID` INT(11) NOT NULL AUTO_INCREMENT,
			`title` VARCHAR( 255 ) DEFAULT '' NOT NULL ,
			`description` TEXT DEFAULT '' NOT NULL ,
			PRIMARY KEY ( `ID` )
			)" );
	}
	
	// [ ID ]
	function add( $title, $description ){
		global $wpdb; 
		$wpdb-> query(
		$wpdb-> prepare(
			"INSERT INTO `{$this->name}` (`title`, `description`) VALUES ('%s', '%s')",
			$title, $description ) );
		$results = $wpdb->get_row( 'SELECT LAST_INSERT_ID() as `ID`;', ARRAY_A );
		return $results[ 'ID' ];
	}
	
	// true
	function update( $ID, $title, $description ){
		global $wpdb;
		$result = $wpdb-> query(
		$wpdb-> prepare( 
			"UPDATE `{$this->name}` SET `title` = '%s', `description` = '%s' WHERE `ID` = %d;",
			$title, $description, $ID ) );	
		if ( $result === false ) { return $result; }
		return true;
	}
	
	// [ [*], ... ]
	function getAll( $ascending = true ){
		return $this->_getAll( 'title', $ascending );
	}

}

?>