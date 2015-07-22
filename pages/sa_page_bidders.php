<?php
$currentEventID = get_option( 'sa-current-event', '' );

// defines the CRUD table for Bidders / participants
$crud = new SA_CRUD( 'bidders-form' );

class SA_BidderNumberColumn extends SA_CRUD_EmptyColumn
{
	function renderData( $d ){ echo $d[ $this-> id ]; }
}
$bidderNumberCol = $crud-> col( new SA_BidderNumberColumn( 'ID', 'Bidder #' ) );
$bidderNumberCol->addClass( 'column-bidder-number' );

$nameCol = $crud-> col( new SA_CRUD_Column( 'name', 'Name' ) );
$nameCol->addClass( 'column-name' );

$emailCol = $crud-> col( new SA_CRUD_Column( 'email', 'E-Mail' ) );
$emailCol->addClass( 'column-name' );

// view functions are responsible for ensuring transaction integrity & security 
function doMainView( $crud ){
	global $SA_Tables;
	
	$crud-> renderTable( $SA_Tables-> getBidderList( $currentEventID ) );
	
}

function doAddView( $crud ){
	$crud-> renderInputForm( array(),
		get_admin_url(null, 'admin.php')."?page=sa-bidders",
		array( 'view-mode' => 'add' ) );
}

function doEditView( $crud ){
	
}

// process form submissions
function processPost( $crud ){
	global $SA_Tables;
	
	// add
	if ( isset( $_POST[ 'submit' ] ) ){
		$viewMode = $_POST[ 'view-mode' ];
		if ( $viewMode == 'add' ){
			$entry = $crud-> processInputFormPost();
			$contactID = $SA_Tables-> contacts-> add( $entry[ 'name' ], $entry[ 'email' ] );
			$SA_Tables-> bidders-> add( $currentEventID, $contactID );
		}
	}
}

processPost( $crud );

?>
<style>
.column-bidder-number{
	width: 150px;
}
</style>
<div class="wrap">
<h1><?php _e( "Bidders", 'silentauction' ); ?>&nbsp;
<?php echo '<a href="' . get_admin_url(null, 'admin.php')."?page=sa-bidders&view=add\" class=\"page-title-action\">"
	. __("Add Bidder", 'silentauction') . '</a>' ?>
</h1>
	
<?php
	$viewKey = isset( $_GET[ 'view' ] ) ? $_GET[ 'view' ] : '';
	switch ( $viewKey ){
		case 'add':
			doAddView( $crud );
			break;
		case 'edit':
			doEditView( $crud );
			break;
		default:
			doMainView( $crud );
	}
	
	print_r($_POST);
?>

</div>