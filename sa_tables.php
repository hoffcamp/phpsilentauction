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
require_once 'tables/sa_table_item_sections.php';

class SA_Tables
{
	var $contacts;
	var $bidders;
	var $donations;
	var $items;
	var $events;
	var $itemSections;
	
	function __construct(){
		$this-> contacts = new SA_ContactsTable( 'contacts' );
		$this-> bidders = new SA_BiddersTable( 'bidders' );
		$this-> donations = new SA_DonationsTable( 'donations' );
		$this-> items = new SA_ItemsTable( 'items' );
		$this-> events = new SA_EventsTable( 'events' );
		$this-> itemSections = new SA_ItemSectionsTable( 'item_sections' );
	}
	
	function install(){
		$this-> contacts-> install();
		$this-> bidders-> install();
		$this-> donations-> install();
		$this-> items-> install();
		$this-> events-> install();
		$this-> itemSections-> install();
	}
	
	function uninstall(){
		$this-> contacts-> uninstall();
		$this-> bidders-> uninstall();
		$this-> donations-> uninstall();
		$this-> items-> uninstall();
		$this-> events-> uninstall();
		$this-> itemSections-> uninstall();
	}
	
	//////////////////////////////////////////////////////
	
	function getBidderList( $eventID ){
		global $wpdb;
		$result = $wpdb-> get_results(
			$wpdb->prepare( "SELECT `{$this-> bidders-> name}`.`ID` as `ID`, `{$this-> bidders-> name}`.`bidderNumber`, `{$this-> bidders-> name}`.`expressPay`, 
			`{$this-> contacts-> name}`.name, `{$this-> contacts-> name}`.business, `{$this-> contacts-> name}`.email, `{$this-> contacts-> name}`.addr, `{$this-> contacts-> name}`.city, `{$this-> contacts-> name}`.state, `{$this-> contacts-> name}`.zip 
			FROM `{$this-> bidders-> name}` LEFT OUTER JOIN `{$this-> contacts-> name}` ON `{$this-> bidders-> name}`.`contactID` = `{$this-> contacts-> name}`.`ID`
			WHERE `{$this-> bidders-> name}`.`eventID` = '%d'; ", $eventID ), ARRAY_A );
		return $result;
	}
	
	function getBidderInfo( $eventID, $bidderID ){
		global $wpdb;
		$result = $wpdb-> get_row(
			$wpdb-> prepare( "SELECT `{$this-> bidders-> name}`.`ID` as `ID`, `{$this-> bidders-> name}`.`contactID`, `{$this-> bidders-> name}`.`bidderNumber`, `{$this-> bidders-> name}`.`expressPay`, 
			`{$this-> contacts-> name}`.name, `{$this-> contacts-> name}`.business, `{$this-> contacts-> name}`.email, `{$this-> contacts-> name}`.addr, `{$this-> contacts-> name}`.city, `{$this-> contacts-> name}`.state, `{$this-> contacts-> name}`.zip 
			FROM `{$this-> bidders-> name}` LEFT OUTER JOIN `{$this-> contacts-> name}` ON `{$this-> bidders-> name}`.`contactID` = `{$this-> contacts-> name}`.`ID`
			WHERE `{$this-> bidders-> name}`.`eventID` = '%d' AND `{$this-> bidders-> name}`.`ID` = '%d'; ",
			$eventID, $bidderID ), ARRAY_A );
		return $result;
	}
	
	function updateBidderInfo( $eventID, $bidderID, $bidderNumber, $expressPay, $name, $business, $email, $addr, $city, $state, $zip ){
		global $wpdb;
		$bidderInfo = $this-> getBidderInfo( $eventID, $bidderID );
		$this-> bidders-> update( $bidderID, $bidderNumber, $expressPay );
		$this-> contacts-> update( $bidderInfo[ 'contactID' ], $name, $business, $email, $addr, $city, $state, $zip );
	}
}