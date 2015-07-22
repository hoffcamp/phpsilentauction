<?php

class SA_ContactsTable extends SA_Table
{
	function install(){
		$this->_createTable(
			"CREATE TABLE `".$this->name."` ( 
			`ID` INT(11) NOT NULL AUTO_INCREMENT,							
			`name` VARCHAR( 255 ) DEFAULT '' NOT NULL ,
			`email` VARCHAR( 255 ) DEFAULT '' NOT NULL ,
			PRIMARY KEY ( `ID` )
			)" );
	}
	
	// [ ID ]
	function add( $name, $email ){
		global $wpdb; 
		$wpdb-> query(
		$wpdb-> prepare(
			"INSERT INTO `{$this->name}` (`name`, `email`) VALUES ('%s', '%s')",
			$name, $email ) );
		$results = $wpdb->get_row( 'SELECT LAST_INSERT_ID() as `ID`;', ARRAY_A );
		return $results[ 'ID' ];
	}

	// true on success
	function update( $ID, $name, $email ){
		global $wpdb;
		$result = $wpdb-> query(
		$wpdb-> prepare( 
			"UPDATE `{$this->name}` SET `name` = '%s', `email` = '%s' WHERE `ID` = %d;",
			$name, $email, $ID ) );	
		if ( $result === false ) { return $result; }
		return true;
	}
	
	// [ [*], ... ]
	function getAll( $ascending = true ){
		return $this->_getAll( 'name', $ascending );
	}
}
?>