<?php
// defines the CRUD table for Bidders / participants
$crud = new SA_CRUD( 'bidders-form' );

$actionsCol = $crud-> col( new SA_CRUD_ActionsColumn( '', '', array( 'page' => 'sa-bidders' ) ) );
$actionsCol-> add( new SA_CRUD_Action( 'edit', 'Edit', array( 'view' => 'edit' ) ) );
$actionsCol-> addClass( 'column-actions' );

$crud-> col( new SA_CRUD_Column( 'bidderNumber', "Bid No." ) );

$nameCol = $crud-> col( new SA_CRUD_Column( 'name', 'Name' ) );
$nameCol->addClass( 'column-name' );

$emailCol = $crud-> col( new SA_CRUD_Column( 'email', 'E-Mail' ) );
$emailCol->addClass( 'column-name' );

$crud-> col( new SA_CRUD_Column( 'addr', 'Address' ) )
	->hideColumn();
$crud-> col( new SA_CRUD_Column( 'city', 'City' ) )
	->hideColumn();
$crud-> col( new SA_CRUD_Column( 'state', 'State' ) )
	->hideColumn();
$crud-> col( new SA_CRUD_Column( 'zip', 'Zip' ) )
	->hideColumn();	

class SA_BidderActions extends SA_CRUD_EmptyColumn
{
	function renderData( $rowID, $d ){
		?><table><tr><td>
		
		<form method="get" action="<?php echo get_admin_url(null, 'admin.php')."?page=sa-bidders" ?>">		
		<input type="submit" class="button" name="action-summary" id="action-summary" value="<?php _e("Summary",'silentauction'); ?>" />		
		<input type="hidden" name="id" value="<?php echo $d[ 'bidderNumber' ]; ?>" />
		<input type="hidden" name="page" value="sa-bidders" />
		</form>
		
		</td></tr></table><?php
	}
}
$crud-> col( new SA_BidderActions( '', 'Actions' ) );

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
	global $SA_Tables;
	$currentEventID = get_option( 'sa-current-event', '' );
	$editID = isset( $_GET[ 'crud-row-id' ] ) ? $_GET[ 'crud-row-id' ] : '';
	$entry = $SA_Tables-> getBidderInfo( $currentEventID, $editID );
	$crud-> renderInputForm( $entry,
		get_admin_url(null, 'admin.php')."?page=sa-bidders",
		array( 'view-mode' => 'edit', 'edit-id' => $editID ) );
}

function doSummaryView( $crud ){
	global $SA_Tables;
	$bidderID = $_GET[ 'id' ];
	$action = get_admin_url(null, 'admin.php')."?page=sa-items";
	$form = new SA_Form_BidderSummary( $bidderID, $action );
	$form-> renderForm();
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
			$contactID = $SA_Tables-> contacts-> add( $entry[ 'name' ], '', $entry[ 'email' ], $entry[ 'addr' ], $entry[ 'city' ], $entry[ 'state' ], $entry[ 'zip' ] );
			$SA_Tables-> bidders-> add( $currentEventID, $contactID, $entry[ 'bidderNumber' ] );
		} elseif ( $viewMode == 'edit' ){
			$entry = $crud-> processInputFormPost();
			$editID = $_POST[ 'edit-id' ]; // bidder ID
			$SA_Tables-> updateBidderInfo( $currentEventID, $editID, $entry[ 'bidderNumber' ], $entry[ 'name' ], '', $entry[ 'email' ], $entry[ 'addr' ], $entry[ 'city' ], $entry[ 'state' ], $entry[ 'zip' ] );			
		}
	}
}

$currentEventID = get_option( 'sa-current-event' , '' );
$showPage = ( $currentEventID != '' );

?>
<style>
.column-actions {
	width: 30px;
}
.column-bidder-number{
	width: 150px;
}
.column-email{
	width: 20%;
}
</style>
<div class="wrap">

<?php

$subtitle = __( "Bidders", 'silentauction' );

if ( $showPage ){ $subtitle .= ' <a href="' . get_admin_url(null, 'admin.php')."?page=sa-bidders&view=add\" class=\"page-title-action\">"
	. __("Add Bidder", 'silentauction') . '</a>'; }

sa_heading( $subtitle ); ?>
	
<?php
	if ( $showPage ){
		processPost( $crud );
		
		$actions = array( 'action-summary' );
		$viewKey = '';
		foreach ( $actions as $a ){ if ( isset( $_GET[ $a ] ) ){ $viewKey = $a; } }
		
		if ( $viewKey == '' && isset( $_GET[ 'view' ] ) ){
			$viewKey = $_GET[ 'view' ];
		}
		
		switch ( $viewKey ){
			case 'add':
				doAddView( $crud );
				break;
			case 'edit':
				doEditView( $crud );
				break;
			case 'action-summary':
				doSummaryView( $crud );
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
?>

</div>