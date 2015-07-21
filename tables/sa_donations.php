<?php

class SA_DonationsTable extends SA_Table
{
	function install(){
		$this->_createTable(
			"CREATE TABLE `".$this->name."` ( 
			`ID` INT(11) NOT NULL AUTO_INCREMENT,				
			`contactID` INT DEFAULT '0' NOT NULL ,
			`amount` FLOAT DEFAULT '0.0' NOT NULL ,
			`type` VARCHAR( 32 ) DEFAULT '' NOT NULL ,
			PRIMARY KEY ( `ID` )
			)" );
	}
}

?>