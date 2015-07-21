<?php

class SA_Capabilities
{
	var $membersExists;
	
	function __construct(){
		
		////////////////////////////////////////////////////////////////////
		
		$this->caps = array(
			'silent_auction_superadmin',
			'silent_auction_admin',
			'silent_auction_user',
		);
		
		////////////////////////////////////////////////////////////////////
		
		$this->membersExists = false;
		
		if ( class_exists( 'Members_Load' ) ) {
			$this->membersExists = true;
			$this->capLUT = array();
			
			foreach( $this->caps as $cap ){
				add_filter( $cap, array($this, $cap) );
				$this->capLUT[ $cap ] = true;
			}

			add_filter( 'members_get_capabilities', 	array($this,'add_members_capabilities') ); 
		}
	}
	
	function __call( $name, $cap ){
		if ( isset( $this->capLUT[ $cap ] ) ){
			return $cap;
		}
	}
	
	public function add_members_capabilities( $caps ){
		foreach( $this->caps as $cap ){
			$caps[] = $cap;
		}
		return $caps;
	}
}