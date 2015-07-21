<?php

/*
All table query methods return boolean false on failure,
or an associative array on success. Keys are listed above the method.

install() and uninstall() return no values.
*/

require_once 'tables/sa_table.php';
require_once 'tables/sa_contacts.php';
require_once 'tables/sa_bidders.php';
require_once 'tables/sa_donations.php';
require_once 'tables/sa_items.php';

class SA_Tables
{
	var $contacts;
	var $bidders;
	var $donations;
	var $items;
	
	function __construct(){
		$this-> contacts = new SA_ContactsTable( 'contacts' );
		$this-> bidders = new SA_BiddersTable( 'bidders' );
		$this-> donations = new SA_DonationsTable( 'donations' );
		$this-> items = new SA_ItemsTable( 'items' );
	}
	
	function install(){
		$this-> contacts-> install();
		$this-> bidders-> install();
		$this-> donations-> install();
		$this-> items-> install();
	}
	
	function uninstall(){
		$this-> contacts-> uninstall();
		$this-> bidders-> uninstall();
		$this-> donations-> uninstall();
		$this-> items-> uninstall();
	}
}