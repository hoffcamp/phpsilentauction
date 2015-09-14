<?php

// defines the CRUD table for Events
$crud = new SA_CRUD( 'event-form' );

$crud-> col( new SA_CRUD_ActionsColumn( '', '', array( 'page' => 'sa-items' ) ) )
	-> add( new SA_CRUD_Action( 'edit', 'Edit', array( 'view' => 'edit' ) ) )
	-> addClass( 'column-actions' );

$crud-> col( new SA_CRUD_Column( 'ID', 'Item #' ) )
	->addClass( 'column-value' )
	->disableInput();
	
$crud-> col( new SA_CRUD_Column( 'title', 'Title' ) )
	->addClass( 'column-title' )
	->addClass( 'column-primary' );

$crud-> col( new SA_CRUD_Column( 'description', 'Description' ) )
	->addClass( 'column-description' );

$crud-> col( new SA_CRUD_FloatColumn( 'value', 'Value', "$%.2f" ) )
	->addClass( 'column-value' );
$crud-> col( new SA_CRUD_FloatColumn( 'startBid', 'Starting Bid', "$%.2f" ) )
	->addClass( 'column-startBid' );
$crud-> col( new SA_CRUD_FloatColumn( 'minIncrease', 'Increase', "$%.2f" ) )
	->addClass( 'column-minIncrease' );
	
$crud-> col( new SA_CRUD_BooleanColumn( 'paid', 'Paid', 'yes', '' ) )
	->addClass( 'column-paid' )
	->disableInput();
	
$crud-> col( new SA_CRUD_Column( 'winningBidderID', 'Winner' ) )
	->addClass( 'column-winningBidderID' )
	->disableInput();
$crud-> col( new SA_CRUD_FloatColumn( 'winningBid', 'Winning Bid', "$%.2f" ) )
	->addClass( 'column-winningBid' )
	->disableInput();
	
// Name & address
$crud-> col( new SA_CRUD_Column( 'name', 'Contact Name' ) )
	->hideColumn();
$crud-> col( new SA_CRUD_Column( 'business', 'Business' ) )
	->hideColumn();
$crud-> col( new SA_CRUD_Column( 'email', 'E-Mail' ) )
	->hideColumn();
$crud-> col( new SA_CRUD_Column( 'addr', 'Address' ) )
	->hideColumn();
$crud-> col( new SA_CRUD_Column( 'city', 'City' ) )
	->hideColumn();
$crud-> col( new SA_CRUD_Column( 'state', 'State' ) )
	->hideColumn();
$crud-> col( new SA_CRUD_Column( 'zip', 'Zip' ) )
	->hideColumn();	
	
class SA_ItemActions extends SA_CRUD_EmptyColumn
{
	function renderData( $rowID, $d ){
		?><table><tr><td>
		
		<form method="get" action="<?php echo get_admin_url(null, 'admin.php')."?page=sa-items" ?>">
		<?php if ( $d[ 'winningBidderID' ] == 0 ): ?>
		<input type="submit" class="button" name="action-close" id="action-close" value="<?php _e("Close",'silentauction'); ?>" />
		<?php else: ?>
		<input type="submit" class="button" name="action-reopen" id="action-reopen" value="<?php _e("Reopen",'silentauction'); ?>" />
		<?php  endif; ?>
		
		<input type="hidden" name="id" value="<?php echo $d[ 'ID' ]; ?>" />
		<input type="hidden" name="page" value="sa-items" />
		</form></td></tr></table><?php
	}
}
$crud-> col( new SA_ItemActions( '', 'Actions' ) );

function doMainView( $crud ){
	global $SA_Tables;
	$currentEventID = get_option( 'sa-current-event', '' );
	$crud-> renderTable( $SA_Tables-> items-> getAll( $currentEventID, true ) );	
}

function doAddView( $crud ){
	$crud-> renderInputForm( array(),
		get_admin_url(null, 'admin.php')."?page=sa-items",
		array( 'view-mode' => 'add' ) );
}

function doEditView( $crud ){
	global $SA_Tables;
	$editID = isset( $_GET[ 'crud-row-id' ] ) ? $_GET[ 'crud-row-id' ] : '';
	$entry = $SA_Tables-> items-> getByID( $editID );
	$contactInfo = $SA_Tables-> contacts-> getByID( $entry[ 'contactID' ] );
	$entry = array_merge( $entry, $contactInfo );
	
	$crud-> renderInputForm( $entry,
		get_admin_url(null, 'admin.php')."?page=sa-items",
		array( 'view-mode' => 'edit', 'edit-id' => $editID ) );
}

function doPaymentView( $crud ){
	global $SA_Tables;
	$itemID = $_GET[ 'id' ];
	$action = get_admin_url(null, 'admin.php')."?page=sa-items";
	$form = new SA_Form_ItemPayment( $itemID, $action );
	$form-> renderForm();
}

function doCloseView( $crud ){
	global $SA_Tables;
	$itemID = $_GET[ 'id' ];
	$action = get_admin_url(null, 'admin.php')."?page=sa-items";
	$form = new SA_Form_CloseItem( $itemID, $action );
	$form-> renderForm();
}

function doReopenView( $crud ){
	global $SA_Tables;
	$itemID = $_GET[ 'id' ];
	$action = get_admin_url(null, 'admin.php')."?page=sa-items";
	$form = new SA_Form_ReopenItem( $itemID, $action );
	$form-> renderForm();
}

function processPost( $crud ){
	global $SA_Tables;
	$currentEventID = get_option( 'sa-current-event' , '' );
	
	// add & edit
	if ( isset( $_POST[ 'view-mode' ] ) ){
		$viewMode = $_POST[ 'view-mode' ];
		if ( $viewMode == 'add' ){
			$entry = $crud-> processInputFormPost();
			// add a contact
			$contactID = $SA_Tables-> contacts-> add( $entry[ 'name' ], $entry[ 'business' ], $entry[ 'email' ], $entry[ 'addr' ], $entry[ 'city' ], $entry[ 'state' ], $entry[ 'zip' ] );
			// add an item			
			$SA_Tables-> items-> add( $currentEventID,
				$entry[ 'title' ], $entry[ 'description' ], $entry[ 'value' ], $entry[ 'startBid' ], $entry[ 'minIncrease' ], $contactID );
		} elseif ( $viewMode == 'edit' ){
			$entry = $crud-> processInputFormPost();
			$editID = $_POST[ 'edit-id' ];			
			// update contact
			$contactID = $SA_Tables-> items-> getContactID( $editID );
			$SA_Tables-> contacts-> update( $contactID, $entry[ 'name' ], $entry[ 'business' ], $entry[ 'email' ], $entry[ 'addr' ], $entry[ 'city' ], $entry[ 'state' ], $entry[ 'zip' ] );
			// update item			
			$SA_Tables-> items-> update( $editID,
				$entry[ 'title' ], $entry[ 'description' ], $entry[ 'value' ], $entry[ 'startBid' ], $entry[ 'minIncrease' ] );
		}
	}
	
	// actions
	elseif ( isset( $_POST[ 'action-payment-submit' ] ) ){
		$id = $_POST[ 'id' ];
		$form = new SA_Form_ItemPayment( $id );
		$form-> processFormPost();
	}
	elseif ( isset( $_POST[ 'action-close-submit' ] ) ){
		$id = $_POST[ 'id' ];
		$form = new SA_Form_CloseItem( $id );
		$form-> processFormPost();
	}
	elseif ( isset( $_POST[ 'action-reopen-submit' ] ) ){
		$id = $_POST[ 'id' ];
		$form = new SA_Form_ReopenItem( $id );
		$form-> processFormPost();
	}
}

$currentEventID = get_option( 'sa-current-event' , '' );
$showPage = ( $currentEventID != '' );

?>
<style>
.column-actions {
	width: 30px;
}
</style>
<div class="wrap">
<?php
$subtitle = __( "Items", 'silentauction' );

if ( $showPage ){ $subtitle .= ' <a href="' . get_admin_url(null, 'admin.php')."?page=sa-items&view=add\" class=\"page-title-action\">" . __("Add Item", 'silentauction') . '</a>'; }

sa_heading( $subtitle ); ?>

<?php
	if ( $showPage ){
		processPost( $crud );
		
		$actions = array( 'action-payment', 'action-close', 'action-reopen' );
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
			case 'action-payment':
				doPaymentView( $crud );
				break;
			case 'action-close':
				doCloseView( $crud );
				break;
			case 'action-reopen':
				doReopenView( $crud );
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