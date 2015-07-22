<?php

// defines the CRUD table for Events
$crud = new SA_CRUD( 'event-form' );

$titleCol = $crud-> col( new SA_CRUD_Column( 'title', 'Title' ) );
$titleCol->addClass( 'column-name' );
$titleCol->addClass( 'column-primary' );

class SA_EventActions extends SA_CRUD_EmptyColumn
{
	function renderData( $d ){
		?>
		<form method="post" action="<?php echo get_admin_url(null, 'admin.php')."?page=sa-events" ?>">
		<?php if ( $d[ 'ID' ] == get_option( 'sa-current-event', '' ) ): ?>
		<input type="submit" name="action-deactivate" id="action-deactivate" class="button" value="Deactivate"  />
		<?php else: ?>
		<input type="submit" name="action-activate" id="action-activate" class="button" value="Activate"  />
		<?php endif; ?>
		<input type="hidden" name="id" value="<?php echo $d[ 'ID' ]; ?>" />
		</form>
		<?php
	}
}
$actionsCol = $crud-> col( new SA_EventActions( '', 'Actions' ) );
$actionsCol->addClass( 'column-name' );

$descriptionCol = $crud-> col( new SA_CRUD_Column( 'description', 'Description' ) );
$descriptionCol->addClass( 'column-description' );

// view functions are responsible for ensuring transaction integrity & security 
function doMainView( $crud ){
	global $SA_Tables;

	$crud-> activeRow( get_option( 'sa-current-event', '' ) );
	$crud-> renderTable( $SA_Tables-> events-> getAll() );
}

function doAddView( $crud ){
	$crud-> renderInputForm( array(),
		get_admin_url(null, 'admin.php')."?page=sa-events",
		array( 'view-mode' => 'add' ) );
}

function doEditView( $crud ){
	$editID = isset( $_GET[ 'id' ] ) ? $_GET[ 'id' ] : '';
}

// process form submissions
function processPost( $crud ){
	global $SA_Tables;
	
	// add
	if ( isset( $_POST[ 'submit' ] ) ){
		$viewMode = $_POST[ 'view-mode' ];
		if ( $viewMode == 'add' ){
			$entry = $crud-> processInputFormPost();
			$SA_Tables-> events-> add( $entry[ 'title' ], $entry[ 'description' ] );		
		}
	}
	
	// actions
	elseif ( isset( $_POST[ 'action-activate' ] ) ){
		$id = $_POST[ 'id' ];
		update_option( 'sa-current-event', $id );
	}
	elseif ( isset( $_POST[ 'action-deactivate' ] ) ){
		$id = $_POST[ 'id' ];
		if ( get_option( 'sa-current-event', '' ) == $id ){
			update_option( 'sa-current-event', '' );
		}
	}
}

processPost( $crud );

?>

<div class="wrap">
<h1><?php _e( "Events", 'silentauction' ); ?>&nbsp;
<?php echo '<a href="' . get_admin_url(null, 'admin.php')."?page=sa-events&view=add\" class=\"page-title-action\">"
	. __("Add Event", 'silentauction') . '</a>' ?>
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