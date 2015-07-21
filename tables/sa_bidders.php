<?php 
class SA_BiddersTable extends SA_Table
{
	function install(){
		$this->_createTable(
			"CREATE TABLE `".$this->name."` ( 
			`ID` INT(11) NOT NULL AUTO_INCREMENT,				
			`contactID` INT DEFAULT '0' NOT NULL ,
			`bidderNumber` INT DEFAULT '0' NOT NULL ,
			PRIMARY KEY ( `ID` )
			)" );
	}
}
?>