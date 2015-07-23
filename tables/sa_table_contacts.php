<?php

class SA_ContactsTable extends SA_Table
{
	function install(){
		$this->_createTable(
			"CREATE TABLE `".$this->name."` ( 
			`ID` INT(11) NOT NULL AUTO_INCREMENT,
			`firstName` VARCHAR( 127 ) DEFAULT '' NOT NULL ,
			`lastName` VARCHAR( 127 ) DEFAULT '' NOT NULL ,
			`email` VARCHAR( 255 ) DEFAULT '' NOT NULL ,
			PRIMARY KEY ( `ID` )
			)" );
	}
	
	// [ ID ]
	function add( $firstName, $lastName, $email ){
		global $wpdb; 
		$wpdb-> query(
		$wpdb-> prepare(
			"INSERT INTO `{$this->name}` (`firstName`, `lastName`, `email`) VALUES ('%s', '%s', '%s')",
			$firstName, $lastName, $email ) );
		$results = $wpdb->get_row( 'SELECT LAST_INSERT_ID() as `ID`;', ARRAY_A );
		return $results[ 'ID' ];
	}

	// true on success
	function update( $ID, $firstName, $lastName, $email ){
		global $wpdb;
		$result = $wpdb-> query(
		$wpdb-> prepare( 
			"UPDATE `{$this->name}` SET `firstName` = '%s', `lastName` = '%s', `email` = '%s' WHERE `ID` = %d;",
			$firstName, $lastName, $email, $ID ) );	
		if ( $result === false ) { return $result; }
		return true;
	}
	
	// [ [*], ... ]
	function getAll( $ascending = true ){
		return $this->_getAll( 'name', $ascending );
	}
}
?>