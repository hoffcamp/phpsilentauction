<?php

/*
All table query methods return boolean false on failure,
or an associative array on success. Keys are listed above the method.

install() and uninstall() return no values.
*/

require_once 'tables/sa_table.php';
require_once 'tables/sa_table_contacts.php';
require_once 'tables/sa_table_bidders.php';
require_once 'tables/sa_table_donations.php';
require_once 'tables/sa_table_items.php';
require_once 'tables/sa_table_events.php';

class SA_Tables
{
	var $contacts;
	var $bidders;
	var $donations;
	var $items;
	var $events;
	
	function __construct(){
		$this-> contacts = new SA_ContactsTable( 'contacts' );
		$this-> bidders = new SA_BiddersTable( 'bidders' );
		$this-> donations = new SA_DonationsTable( 'donations' );
		$this-> items = new SA_ItemsTable( 'items' );
		$this-> events = new SA_EventsTable( 'events' );
	}
	
	function install(){
		$this-> contacts-> install();
		$this-> bidders-> install();
		$this-> donations-> install();
		$this-> items-> install();
		$this-> events-> install();
	}
	
	function uninstall(){
		$this-> contacts-> uninstall();
		$this-> bidders-> uninstall();
		$this-> donations-> uninstall();
		$this-> items-> uninstall();
		$this-> events-> uninstall();
	}
	
	//////////////////////////////////////////////////////
	
	function getBidderList( $eventID ){
		global $wpdb;
		$result = $wpdb-> get_results(
			"SELECT `{$this-> bidders-> name}`.`ID` as `ID`, `{$this-> contacts-> name}`.`name`, `{$this-> contacts-> name}`.`email` FROM `{$this-> bidders-> name}` WHERE `{$this-> bidders-> name}`.`eventID` = '{$eventID}' `LEFT OUTER JOIN `{$this-> contacts-> name}` ON `{$this-> bidders-> name}`.`contactID` = `{$this-> contacts-> name}`.`ID`; "
		, ARRAY_A );
		return $result;
	}
}