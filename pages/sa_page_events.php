<?php

// defines the CRUD table for Events
$crud = new SA_CRUD( 'event-form' );

$titleCol = $crud-> col( new SA_CRUD_Column( 'title', 'Title' ) );
$titleCol->addClass( 'column-title' );
$titleCol->addClass( 'column-primary' );

$crudActions = $crud-> col( new SA_CRUD_ActionsColumn( '', 'Actions' ) );
$crudActions-> add( new SA_CRUD_Action( 'action-edit', 'Edit Details',
	array( 'view' => 'edit', 'page' => 'sa-events' ) ) );
$crudActions->addClass( 'column-actions' );

class SA_EventActions extends SA_CRUD_EmptyColumn
{
	function renderData( $rowID, $d ){
		?><table><tr><td>
		<form method="post" action="<?php echo get_admin_url(null, 'admin.php')."?page=sa-events" ?>">
		<?php if ( $d[ 'ID' ] == get_option( 'sa-current-event', '' ) ): ?>
		<input type="submit" name="action-deactivate" id="action-deactivate" class="button" value="Deactivate"  />
		<?php else: ?>
		<input type="submit" name="action-activate" id="action-activate" class="button" value="Activate"  />
		<?php endif; ?>
		<input type="hidden" name="id" value="<?php echo $d[ 'ID' ]; ?>" />
		</form></td></tr></table>
		<?php
	}
}
$actionsCol = $crud-> col( new SA_EventActions( '', '' ) );
$actionsCol->addClass( 'column-actions' );

$descriptionCol = $crud-> col( new SA_CRUD_Column( 'description', 'Description' ) );
$descriptionCol-> addClass( 'column-description' );

class SA_ItemSections extends SA_CRUD_EmptyColumn
{	
	/*function hasInput(){ return true; }
	function renderInput( $rowID, $d ){
		global $SA_Tables;
		
		$sections = $SA_Tables-> itemSections-> getAll( $d[ 'ID' ] );
		
		$inputIndex = 0;
		foreach ( $sections as $s ){
			$id = 'input-' . $this->id . '-' . $inputIndex;
			echo "<input type=\"text\" name=\"{$id}\" id=\"{$id}\" value=\"{$s[ 'title' ]}\" class=\"regular-text\"/>";	
			echo "<input type=\"hidden\" name=\"{$this->id}-id-{$inputIndex}\" value=\"{$s['ID']}\" />";
			$inputIndex++;
		}
		
		echo "<input type=\"hidden\" name=\"{$this->id}-listcount\" value=\"{$inputIndex}\" />";
	}*/
}
$sectionsCol = $crud-> col( new SA_ItemSections( '', 'Sections' ) )
	->hideColumn()
	->addClass( 'column-sections' );

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
	global $SA_Tables;
	$editID = isset( $_GET[ 'crud-row-id' ] ) ? $_GET[ 'crud-row-id' ] : '';
	$entry = $SA_Tables-> events-> getByID( $editID );
	$crud-> renderInputForm( $entry,
		get_admin_url(null, 'admin.php')."?page=sa-events",
		array( 'view-mode' => 'edit', 'edit-id' => $editID ) );
}

// process form submissions
function processPost( $crud ){
	global $SA_Tables;
	
	// add & edit
	if ( isset( $_POST[ 'submit' ] ) ){
		$viewMode = $_POST[ 'view-mode' ];
		if ( $viewMode == 'add' ){
			$entry = $crud-> processInputFormPost();
			$eventID, $SA_Tables-> events-> add( $entry[ 'title' ], $entry[ 'description' ] );
			// add a single item section
			$SA_Tables-> itemSections-> add( $eventID, 'Auction Items' );
		} elseif ( $viewMode == 'edit' ){
			$entry = $crud-> processInputFormPost();
			$SA_Tables-> events-> update( $_POST[ 'edit-id' ], $entry[ 'title' ], $entry[ 'description' ] );
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
<style>
.column-actions {
	width: 100px;
}
.column-title {
	width: 25%;
}
</style>
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
?>

</div>