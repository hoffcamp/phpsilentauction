<?php
// kr8EYU!n2sd#b^7Ja7)qlXuw

class SA_Options
{
	var $defaultOptions;
	
	function __construct(){
		$this->defaultOptions = array(
			'sa-current-event' => ''
		);
	}
	
	function install(){
		foreach( $this->defaultOptions as $optionName => $defaultValue ){
			update_option( $optionName, get_option( $optionName, $defaultValue ) );
		}
	}
	
	function uninstall(){
		foreach( $this->defaultOptions as $optionName => $defaultValue ){
			delete_option( $optionName );
		}
	}
}

?>