<div class="wrap">
<?php
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
	
	$uploadData = $_SESSION[ 'sa-import-data' ];
	unset( $_SESSION[ 'sa-import-data' ] );
	
	foreach( $uploadData as $entry ){
		// add a contact
		$contactID = $SA_Tables-> contacts-> add( $entry[ 'name' ], $entry[ 'business' ], $entry[ 'email' ], $entry[ 'addr' ], $entry[ 'city' ], $entry[ 'state' ], $entry[ 'zip' ] );
		// add an item
		$SA_Tables-> items-> add( $currentEventID,
			$entry[ 'title' ], $entry[ 'description' ], $entry[ 'value' ], $entry[ 'startBid' ], $entry[ 'minIncrease' ], $contactID );
	}	
	
	echo "<p>" . sprintf( __( "Imported %d items", 'silentauction' ), sizeof($uploadData) ) . "</p>";
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
	
	echo "<p>" . sprintf( __( "Imported %d bidders", 'silentauction' ), sizeof($uploadData) ) . "</p>";
}

function doDefault(){
	global $itemUploadAction;
	$action = get_admin_url(null, 'admin.php')."?page=sa-import";
	
	$form = new SA_Form_ItemsUpload( $action );
	$form->renderForm( 'action-item-upload' );
	
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