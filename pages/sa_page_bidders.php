<?php
// defines the CRUD table for Bidders / participants
$crud = new SA_CRUD( 'bidders-form' );

class SA_BidderNumberColumn extends SA_CRUD_EmptyColumn
{
	function renderData( $d ){ echo $d[ $this-> id ]; }
}
$bidderNumberCol = $crud-> col( new SA_BidderNumberColumn( 'ID', 'Bidder #' ) );
$bidderNumberCol->addClass( 'column-bidder-number' );

$nameCol = $crud-> col( new SA_CRUD_Column( 'prefix', 'Prefix' ) );
$nameCol->addClass( 'column-name' );

$nameCol = $crud-> col( new SA_CRUD_Column( 'firstName', 'First' ) );
$nameCol->addClass( 'column-name' );

$nameCol = $crud-> col( new SA_CRUD_Column( 'lastName', 'Last' ) );
$nameCol->addClass( 'column-name' );

$emailCol = $crud-> col( new SA_CRUD_Column( 'email', 'E-Mail' ) );
$emailCol->addClass( 'column-name' );

// view functions are responsible for ensuring transaction integrity & security 
function doMainView( $crud ){
	global $SA_Tables;
	$currentEventID = get_option( 'sa-current-event', '' );
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
	
	$currentEventID = get_option( 'sa-current-event', '' );
	
	// add
	if ( isset( $_POST[ 'submit' ] ) ){
		$viewMode = $_POST[ 'view-mode' ];
		if ( $viewMode == 'add' ){
			$entry = $crud-> processInputFormPost();
			$contactID = $SA_Tables-> contacts-> add( $entry[ 'prefix' ], $entry[ 'firstName' ], $entry[ 'lastName' ], $entry[ 'email' ] );
			$SA_Tables-> bidders-> add( $currentEventID, $contactID );
		}
	}
}

$currentEventID = get_option( 'sa-current-event' , '' );
$showPage = ( $currentEventID != '' );

?>
<style>
.column-bidder-number{
	width: 150px;
}
</style>
<div class="wrap">
<h1><?php _e( "Bidders", 'silentauction' ); ?>&nbsp;
<?php if ( $showPage ): ?>
<?php echo '<a href="' . get_admin_url(null, 'admin.php')."?page=sa-bidders&view=add\" class=\"page-title-action\">"
	. __("Add Bidder", 'silentauction') . '</a>' ?>
<?php endif; ?>
</h1>
	
<?php
	if ( $showPage ){
		processPost( $crud );
		
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
	} else {
		$eventsPageKey = 'sa-events';
		$linkClassString = 'page-title-action';
		echo '<p><a href="' . get_admin_url(null, 'admin.php')."?page={$eventsPageKey}\" class=\"{$linkClassString}\">"
			. __("Click here to activate an event.", 'silentauction') . '</a></p>';
	}
	print_r($_POST);
?>

</div>