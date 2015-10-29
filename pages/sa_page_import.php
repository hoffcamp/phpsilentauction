<div class="wrap">
<?php

global $SA_DIR;
require( $SA_DIR . 'PHPExcel-1.8/Classes/PHPExcel.php' );

$currentEventID = get_option( 'sa-current-event' , '' );
$showPage = ( $currentEventID != '' );

$subtitle = __( "Import", 'silentauction' );
sa_heading( $subtitle );

$actionKeys = array();

function doItemUploadVerify(){	
	global $SA_Tables;
	$currentEventID = get_option( 'sa-current-event' , '' );
	$action = get_admin_url(null, 'admin.php')."?page=sa-import";	
	
	$form = new SA_Form_ItemsUpload( $action );
	$uploadData = $form->processPost();
	
	$_SESSION[ 'sa-import-data' ] = $uploadData;
	
	$form->verifyData( $uploadData, 'action-item-verify' );		
}

function doItemUploadFinal(){
	global $SA_Tables;
	$currentEventID = get_option( 'sa-current-event' , '' );	
	$currentSectionID = 1;
	
	$uploadData = $_SESSION[ 'sa-import-data' ];
	unset( $_SESSION[ 'sa-import-data' ] );
	
	foreach( $uploadData as $entry ){
		// add a contact
		$contactID = $SA_Tables-> contacts-> add( $entry[ 'name' ], $entry[ 'business' ], $entry[ 'email' ], $entry[ 'addr' ], $entry[ 'city' ], $entry[ 'state' ], $entry[ 'zip' ] );
		// add an item
		$SA_Tables-> items-> add( $currentEventID, $currentSectionID,
			$entry[ 'title' ], $entry[ 'description' ], $entry[ 'value' ], $entry[ 'startBid' ], $entry[ 'minIncrease' ], $contactID );
	}	
	
	echo '<div id="message" class="updated">';
	echo "<p>" . sprintf( __( "Imported %d items", 'silentauction' ), sizeof($uploadData) ) . "</p>";
	echo '</div>';
}

function doBidderUploadVerify(){
	global $SA_Tables;
	$currentEventID = get_option( 'sa-current-event' , '' );
	$action = get_admin_url(null, 'admin.php')."?page=sa-import";	
	
	$form = new SA_Form_BiddersUpload( $action );
	$uploadData = $form->processPost();
	
	$_SESSION[ 'sa-import-data' ] = $uploadData;
	
	$form->verifyData( $uploadData, 'action-bidder-verify' );		
}

function doBidderUploadFinal(){
	global $SA_Tables;
	$currentEventID = get_option( 'sa-current-event' , '' );	
	
	$uploadData = $_SESSION[ 'sa-import-data' ];
	unset( $_SESSION[ 'sa-import-data' ] );
	
	foreach( $uploadData as $entry ){
		// add a contact
		$contactID = $SA_Tables-> contacts-> add( $entry[ 'name' ], '', $entry[ 'email' ], $entry[ 'addr' ], $entry[ 'city' ], $entry[ 'state' ], $entry[ 'zip' ] );
		// add a bidder
		$SA_Tables-> bidders-> add( $currentEventID, $contactID, $entry[ 'bidderNumber' ] );
	}
	
	echo '<div id="message" class="updated">';
	echo "<p>" . sprintf( __( "Imported %d bidders", 'silentauction' ), sizeof($uploadData) ) . "</p>";
	echo '</div>';
}

function doDefault(){
	global $itemUploadAction;
	$action = get_admin_url(null, 'admin.php')."?page=sa-import";
	
	?>
	<h2>Import Auction Items</h2>
	<p>
	Must be an Excel file (.xls or .xlsx), with column names in the first row. Only the following columns will be imported:
	<ul>
		<li>Contact Name</li>
		<li>Business</li>
		<li>Address	City</li>
		<li>State</li>
		<li>Zip</li>
		<li>Description</li>
		<li>Value</li>
		<li>Start Bid</li>
		<li>Bid Increase</li>
		<li>Long Description</li>
	</ul>	
	</p>
	<p>
	<i>Data must be on the first sheet.</i>
	</p>
	<?php
	$form = new SA_Form_ItemsUpload( $action );
	$form->renderForm( 'action-item-upload' );
	?>
	<hr />
	<h2>Import Bidders</h2>
	<p>
	Must be an Excel file (.xls or .xlsx), with column names in the first row. Only the following columns will be imported:
	<ul>
		<li>Full Name</li>
		<li>Address</li>
		<li>City</li>
		<li>State</li>
		<li>Zip</li>
		<li>Email</li>
		<li>Bid No.</li>
	</ul>	
	</p>
	<p>
	<i>Data must be on the first sheet.</i>
	</p>
	<?php
	$bidderForm = new SA_Form_BiddersUpload( $action );
	$bidderForm->renderForm( 'action-bidder-upload' );
}

//////////////////////////////////////////
// Render page

if ( $showPage ){	

	if ( isset( $_POST[ 'action-item-upload' ] ) ){
		doItemUploadVerify();
	} elseif ( isset( $_POST[ 'action-item-verify' ] ) ){
		doItemUploadFinal();
		doDefault();
	} elseif ( isset( $_POST[ 'action-bidder-upload' ] ) ){
		doBidderUploadVerify();
	} elseif ( isset( $_POST[ 'action-bidder-verify' ] ) ){
		doBidderUploadFinal();
		doDefault();
	} else {
		doDefault();
	}
}

?>

</div>