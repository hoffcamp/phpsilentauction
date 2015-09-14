<div class="wrap">
<?php
$currentEventID = get_option( 'sa-current-event' , '' );
$showPage = ( $currentEventID != '' );

$subtitle = __( "Import", 'silentauction' );
sa_heading( $subtitle );

$actionKeys = array();

function processPost(){
	global $itemUploadAction;
	if ( isset( $_POST[ 'action-item-upload' ] ) ){
		$form = new SA_Form_ItemsUpload( $action, 'action-item-upload' );
		$form->processPost();
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