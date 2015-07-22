<?php

class SA_DonationsTable extends SA_Table
{
	// 'type' - can be 'sponsor', 'donor'
	
	function install(){
		$this->_createTable(
			"CREATE TABLE `".$this->name."` ( 
			`ID` INT(11) NOT NULL AUTO_INCREMENT,
			`eventID` INT(11) DEFAULT '0' NOT NULL ,
			`contactID` INT DEFAULT '0' NOT NULL ,
			`amount` FLOAT DEFAULT '0.0' NOT NULL ,
			`type` VARCHAR( 32 ) DEFAULT '' NOT NULL ,
			PRIMARY KEY ( `ID` )
			)" );
	}
	
	// [ ID ]
	function add( $eventID, $contactID, $amount, $type ){}
	
	// true
	function update( $ID, $contactID, $amount, $type ){}
	
	// true
	function remove( $ID ){}
	
	// [ * ]
	function getByID( $ID ){}
	
	// [ [*], ... ]
	function getAll( $ascending ){}
	
	// [ [*], ... ]
	function getByType( $eventID, $type ){}
	
	// [ [*], ... ]
	function getByContactID( $eventID ){}
	
}

?>