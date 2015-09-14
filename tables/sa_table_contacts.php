<?php

class SA_ContactsTable extends SA_Table
{
	function install(){
		$this->_createTable(
			"CREATE TABLE `".$this->name."` ( 
			`ID` INT(11) NOT NULL AUTO_INCREMENT,
			`firstName` VARCHAR( 127 ) DEFAULT '' NOT NULL ,
			`lastName` VARCHAR( 127 ) DEFAULT '' NOT NULL ,
			`addr` VARCHAR( 255 ) DEFAULT '' NOT NULL ,
			`city` VARCHAR( 127 ) DEFAULT '' NOT NULL ,
			`state` VARCHAR( 8 ) DEFAULT '' NOT NULL ,
			`zip` VARCHAR( 16 ) DEFAULT '' NOT NULL ,
			`email` VARCHAR( 255 ) DEFAULT '' NOT NULL ,
			PRIMARY KEY ( `ID` )
			)" );
	}
	
	// [ ID ]
	function add( $firstName, $lastName, $email, $addr, $city, $state, $zip ){
		global $wpdb; 
		$wpdb-> query(
		$wpdb-> prepare(
			"INSERT INTO `{$this->name}` (`firstName`, `lastName`, `email`, `addr`, `city`, `state`, `zip` ) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s')",
			$firstName, $lastName, $email, $addr, $city, $state, $zip ) );
		$results = $wpdb->get_row( 'SELECT LAST_INSERT_ID() as `ID`;', ARRAY_A );
		return $results[ 'ID' ];
	}

	// true on success
	function update( $ID, $firstName, $lastName, $email, $addr, $city, $state, $zip ){
		global $wpdb;
		$result = $wpdb-> query(
		$wpdb-> prepare( 
			"UPDATE `{$this->name}` SET `firstName` = '%s', `lastName` = '%s', `email` = '%s', `addr` = '%s', `city` = '%s', `state` = '%s', `zip` = '%s' WHERE `ID` = %d;",
			$firstName, $lastName, $email, $addr, $city, $state, $zip, $ID ) );	
		if ( $result === false ) { return $result; }
		return true;
	}
	
	// [ [*], ... ]
	function getAll( $ascending = true ){
		return $this->_getAll( 'name', $ascending );
	}
}
?>