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
	function add( $name, $email ){}

	// [ ID,name,email ]
	function getByID( $ID ){}
	
	// [ [ID,name,email], ... ]
	function getAllContacts( $ascending ){}
	
	// [ [ID,name,email], ... ]
	function searchContactsByName( $name, $ascending ){}
	
	// [ [ID,name,email], ... ]
	function searchContactsByEmail( $email, $ascending ){}
}
?>