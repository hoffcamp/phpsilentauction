<?php
// defines the CRUD table for Events
		
$crud = new SA_CRUD( 'event-form' );

$titleCol = $crud-> col( new SA_CRUD_Column( 'title', 'Title' ) );
$titleCol->addClass( 'column-name' );
$titleCol->addClass( 'column-primary' );

$descriptionCol = $crud-> col( new SA_CRUD_Column( 'description', 'Description' ) );
$descriptionCol->addClass( 'column-description' );

// view functions are responsible for ensuring transaction integrity & security 
function doMainView( $crud ){
	global $SA_Tables;

	$crud-> activeRow( get_option( 'sa-current-event', '' ) );
	$crud-> renderTable( $SA_Tables-> events-> getAll() );
}

function doAddView( $crud ){
	$crud-> renderInputForm();
}

?>

<div class="wrap">
<h1><?php _e( "Events", 'silentauction' ); ?></h1>

<?php
	$viewKey = isset( $_GET[ 'crud-view' ] ) ? $_GET[ 'crud-view' ] : '';
	switch ( $viewKey ){
		case 'add':
			doAddView( $crud );
			break;
		default:
			doMainView( $crud );
	}
?>

</div>