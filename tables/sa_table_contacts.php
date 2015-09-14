<?php

class SA_ContactsTable extends SA_Table
{
	function install(){
		$this->_createTable(
			"CREATE TABLE `".$this->name."` ( 
			`ID` INT(11) NOT NULL AUTO_INCREMENT,
			`name` VARCHAR( 128 ) DEFAULT '' NOT NULL ,
			`business` VARCHAR( 256 ) DEFAULT '' NOT NULL ,
			`addr` VARCHAR( 256 ) DEFAULT '' NOT NULL ,
			`city` VARCHAR( 128 ) DEFAULT '' NOT NULL ,
			`state` VARCHAR( 8 ) DEFAULT '' NOT NULL ,
			`zip` VARCHAR( 16 ) DEFAULT '' NOT NULL ,
			`email` VARCHAR( 256 ) DEFAULT '' NOT NULL ,
			PRIMARY KEY ( `ID` )
			)" );
	}
	
	// [ ID ]
	function add( $name, $business, $email, $addr, $city, $state, $zip ){
		global $wpdb; 
		$wpdb-> query(
		$wpdb-> prepare(
			"INSERT INTO `{$this->name}` (`name`, `business`, `email`, `addr`, `city`, `state`, `zip` ) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s')",
			$name, $business, $email, $addr, $city, $state, $zip ) );
		$results = $wpdb->get_row( 'SELECT LAST_INSERT_ID() as `ID`;', ARRAY_A );
		return $results[ 'ID' ];
	}

	// true on success
	function update( $ID, $name, $business, $email, $addr, $city, $state, $zip ){
		global $wpdb;
		$result = $wpdb-> query(
		$wpdb-> prepare( 
			"UPDATE `{$this->name}` SET `name` = '%s', `business` = '%s', `email` = '%s', `addr` = '%s', `city` = '%s', `state` = '%s', `zip` = '%s' WHERE `ID` = %d;",
			$name, $business, $email, $addr, $city, $state, $zip, $ID ) );	
		if ( $result === false ) { return $result; }
		return true;
	}
	
	// [ [*], ... ]
	function getAll( $ascending = true ){
		return $this->_getAll( 'name', $ascending );
	}

}
?>