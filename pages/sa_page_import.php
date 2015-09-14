<div class="wrap">
<?php
error_reporting(E_ALL);

$currentEventID = get_option( 'sa-current-event' , '' );
$showPage = ( $currentEventID != '' );

$subtitle = __( "Import", 'silentauction' );
sa_heading( $subtitle );

$actionKeys = array();

function processPost(){
	global $itemUploadAction;
	global $SA_Tables;
	$currentEventID = get_option( 'sa-current-event' , '' );
	$action = get_admin_url(null, 'admin.php')."?page=sa-import";
	
	if ( isset( $_POST[ 'action-item-upload' ] ) ){
		$form = new SA_Form_ItemsUpload( $action, 'action-item-upload' );
		$uploadData = $form->processPost();
		print_r($uploadData);
		foreach( $uploadData as $entry ){
			// add a contact
			$contactID = $SA_Tables-> contacts-> add( $entry[ 'name' ], $entry[ 'business' ], $entry[ 'email' ], $entry[ 'addr' ], $entry[ 'city' ], $entry[ 'state' ], $entry[ 'zip' ] );
			// add an item
			$SA_Tables-> items-> add( $currentEventID,
				$entry[ 'title' ], $entry[ 'description' ], $entry[ 'value' ], $entry[ 'startBid' ], $entry[ 'minIncrease' ], $contactID );
		}
	}
}

function doDefault(){
	global $itemUploadAction;
	$action = get_admin_url(null, 'admin.php')."?page=sa-import";
	$form = new SA_Form_ItemsUpload( $action, 'action-item-upload' );
	$form->renderForm();
}

//////////////////////////////////////////
// Render page

if ( $showPage ){	
	processPost();
	
	$importSections = array( 'items-verify', 'items-column-select' );

	// get the current page / wizard stage		
	$currentSection = 'default';
	foreach ( $importSections as $s ){
		if ( isset( $_GET[ $s ] ) ){ $currentSection = $s; }
	}

	switch( $currentSection ){
		case 'default':
			doDefault();
			break;
	}			
}

?>

</div>