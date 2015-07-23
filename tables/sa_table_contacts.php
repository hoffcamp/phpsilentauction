<?php

class SA_ContactsTable extends SA_Table
{
	function install(){
		$this->_createTable(
			"CREATE TABLE `".$this->name."` ( 
			`ID` INT(11) NOT NULL AUTO_INCREMENT,
			`prefix` VARCHAR( 31 ) DEFAULT '' NOT NULL ,
			`firstName` VARCHAR( 127 ) DEFAULT '' NOT NULL ,
			`lastName` VARCHAR( 127 ) DEFAULT '' NOT NULL ,
			`email` VARCHAR( 255 ) DEFAULT '' NOT NULL ,
			PRIMARY KEY ( `ID` )
			)" );
	}
	
	// [ ID ]
	function add( $prefix, $firstName, $lastName, $email ){
		global $wpdb; 
		$wpdb-> query(
		$wpdb-> prepare(
			"INSERT INTO `{$this->name}` (`prefix`, `firstName`, `lastName`, `email`) VALUES ('%s', '%s', '%s', '%s')",
			$prefix, $firstName, $lastName, $email ) );
		$results = $wpdb->get_row( 'SELECT LAST_INSERT_ID() as `ID`;', ARRAY_A );
		return $results[ 'ID' ];
	}

	// true on success
	function update( $ID, $prefix, $firstName, $lastName, $email ){
		global $wpdb;
		$result = $wpdb-> query(
		$wpdb-> prepare( 
			"UPDATE `{$this->name}` SET `prefix` = '%s', `firstName` = '%s', `lastName` = '%s', `email` = '%s' WHERE `ID` = %d;",
			$prefix, $firstName, $lastName, $email, $ID ) );	
		if ( $result === false ) { return $result; }
		return true;
	}
	
	// [ [*], ... ]
	function getAll( $ascending = true ){
		return $this->_getAll( 'name', $ascending );
	}
}
?>