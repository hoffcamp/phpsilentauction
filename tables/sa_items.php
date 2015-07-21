<?php

class SA_ItemsTable extends SA_Table
{
	function install(){
		$this->_createTable(
			"CREATE TABLE `".$this->name."` ( 
			`ID` INT(11) NOT NULL AUTO_INCREMENT,				
			`value` FLOAT DEFAULT '0.0' NOT NULL ,
			`startBid` FLOAT DEFAULT '0.0' NOT NULL ,
			`minIncrease` FLOAT DEFAULT '0.0' NOT NULL ,
			`paid` TINYINT(1) DEFAULT '0' NOT NULLL ,
			PRIMARY KEY ( `ID` )
			)" );
	}
}

?>