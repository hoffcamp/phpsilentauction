<?php

class SA_CRUD_Column
{
	var $title;
	var $id;
	var $classes;
	var $_hasInput;
	var $_hideColumn;
	
	function __construct( $id = '', $title = '' ){
		$this-> id = $id;
		$this-> title = $title;
		$this-> classes = array();
		$this-> _hasInput = true;
		$this-> _hideColumn = false;
	}
	
	function addClass( $className ){ $this-> classes[ $className ] = $className; return $this; }	
	function removeClass( $className ){	unset( $this-> classes[ $className ] );	}	
	function getClassString(){ return implode( ' ', $this-> classes ); }
	function renderData( $rowID, $d ){ echo $d[ $this-> id ]; }
	function hasInput(){ return $this-> _hasInput; }
	function disableInput(){ $this-> _hasInput = false; return $this; }
	function hideColumn(){ $this-> _hideColumn = true; return $this; }
	function renderInput( $rowID, $d ){
		$value = isset( $d[ $this->id ] ) ? $d[ $this-> id ] : '';
		$id = 'input-' . $this->id;
		echo "<input type=\"text\" name=\"{$id}\" id=\"{$id}\" value=\"{$value}\" class=\"regular-text\"/>";
	}
	// can return an array
	function getInputValue( $rowID ){
		return stripslashes( $_POST[ 'input-' . $this->id ] );
	}
}

class SA_CRUD_DescriptionColumn extends SA_CRUD_Column
{
	function __construct( $id = '', $title = '', $maxLen = 150 ){
		parent::__construct( $id, $title );
		$this-> maxLen = $maxLen;
	}
	
	function renderData( $rowID, $d ){
		$str = $d[ $this-> id ];
		if ( is_string( $str ) ){
			if ( strlen( $str ) > $this->maxLen - 3 ){
				echo substr( $str, 0, $this->maxLen ) . '...';
			} else {
				echo $str;
			}
		}
	}
	
	function renderInput( $rowID, $d ){
		$value = isset( $d[ $this->id ] ) ? $d[ $this-> id ] : '';
		$id = 'input-' . $this->id;
		//rows="10" cols="50" id="moderation_keys" class="large-text code"
		echo "<textarea class=\"large-text\" rows=\"10\" cols=\"50\" name=\"{$id}\" id=\"{$id}\">{$value}</textarea>";
	}
}

class SA_CRUD_BooleanColumn extends SA_CRUD_Column
{
	var $trueValue;
	var $falseValue;
	
	function __construct( $id = '', $title = '', $trueValue = 'yes', $falseValue = 'no' ){
		parent::__construct( $id, $title );
		$this-> trueValue = $trueValue;
		$this-> falseValue = $falseValue;
	}
	function renderData( $rowID, $d ){
		if ( $d[ $this-> id ] == 1 ){ echo $this-> trueValue; }
		else { echo $this-> falseValue; }
	}
}

class SA_CRUD_FloatColumn extends SA_CRUD_Column
{
	var $format;
	function __construct( $id = '', $title = '', $format = '%f' ){
		parent::__construct( $id, $title );
		$this-> format = $format;
	}
	function renderData( $rowID, $d ){
		echo sprintf( $this-> format, $d[ $this-> id ] );
	}
}

class SA_CRUD_EmptyColumn extends SA_CRUD_Column
{
	function hasInput(){ return false; }
	function getInputValue( $rowID ){ return false; }
	function renderInput( $rowID, $d ){}
	function renderData( $rowID, $d ){}
}

class SA_CRUD_Action
{
	var $hidden;
	var $id;
	var $label;
	
	function __construct( $id, $label, $hidden = array() ){
		$this-> id = $id;
		$this-> label = $label;
		$this-> hidden = $hidden;
	}
}

class SA_CRUD_ActionsColumn extends SA_CRUD_EmptyColumn
{
	var $actions;
	var $hidden;
	
	function __construct( $id, $title, $hidden = array() ){
		parent::__construct( $id, $title );
		$this-> hidden = $hidden;
	}
	
	function renderData( $rowID, $d ){
		?>
<table><tr>
<?php foreach ( $this-> actions as $a ): ?>
<td>
	<form method="get" action="">
	<input type="submit" name="<?php echo htmlspecialchars( $a-> id ); ?>" id="<?php echo htmlspecialchars( $a-> id ); ?>" value="<?php echo htmlspecialchars( $a-> label ); ?>" class="button" />
	<?php foreach ( $this->hidden as $name => $value ): ?>
	<input type="hidden" name="<?php echo $name; ?>" value="<?php echo $value; ?>" />
	<?php endforeach; ?>
	<?php foreach ( $a->hidden as $name => $value ): ?>
	<input type="hidden" name="<?php echo $name; ?>" value="<?php echo $value; ?>" />
	<?php endforeach; ?>
	<input type="hidden" name="crud-row-id" value="<?php echo $rowID; ?>" />
	</form>
</td>
<?php endforeach; ?>
</tr></table>		<?php
	}
	
	function add( $a ){ $this-> actions[] = $a; return $this; }
}

class SA_CRUD_RowClasses
{
	function getRowClasses( $rowData ){ return "inactive"; }
}

class SA_CRUD
{
	var $includeCheckboxes; 
	var $formID; 
	var $cols;
	var $rowIDFieldName;
	var $rowIDPrefix;
	var $activeRows;
	var $submitText;
	
	function __construct( $formID = 'crud-form', $rowClasses = false ){		
		$this-> formID = $formID;
		$this-> includeCheckboxes = false;
		$this-> cols = array();
		$this-> rowIDFieldName = 'ID';
		$this-> rowIDPrefix = $formID . '-';
		$this-> activeRows = array();
		$this-> submitText = "Submit";
		$this-> rowClasses = ( $rowClasses !== false ) ? $rowClasses : new SA_CRUD_RowClasses();		
	}
	
	function col( SA_CRUD_Column $col ){ $this-> cols[] = $col; return $col; }	
	function activeRow( $rowID ){ $this-> activeRows[ $rowID ] = $rowID; }
	
	function processInputFormPost(){
		$d = array();
		foreach ( $this-> cols as $col ){
			if ( $col-> hasInput() !== false ){
				$rowID = $_POST[ 'crud-row-id' ];
				$v = $col-> getInputValue( $rowID );
				if ( is_array( $v ) ){
					foreach ( $v as $k => $value ){
						$d[ $k ] = $value;
					}
				} else {
					$d[ $col-> id ] = $v;
				}
			}
		}
		return $d;
	}
	
	function renderRow( $d ){
		foreach ( $this-> cols as $col ){
			if ( !$col->_hideColumn ){
				$classString = $col->getClassString(); ?>
				<td class='<?php echo $classString; ?>'>
					<?php $col->renderData( $d[ $this->rowIDFieldName ] , $d ); ?>
				</td>
				<?php
			}
		}
	}
	
	function renderTable( $data ){
?>
<table class="wp-list-table widefat plugins" id="crud-table">
	<thead>
	<tr>
		<td  id='cb' class='manage-column column-cb check-column'>
			<?php if ( $this-> includeCheckboxes ): ?>
			<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
			<input id="cb-select-all-1" type="checkbox" />
			<?php endif; ?>
		</td>
		<?php foreach ( $this-> cols as $col ): ?>
			<?php if ( !$col->_hideColumn ): ?>
				<?php $classString = 'manage-column ' . $col->getClassString(); ?>
				<th scope="col" id='<?php echo htmlspecialchars($col->id); ?>' class='<?php echo $classString; ?>'><?php echo htmlspecialchars($col->title); ?></th>
			<?php endif; ?>
		<?php endforeach; ?>
	</tr>
	</thead>

	<tbody id="the-list">
		<?php foreach ( $data as $d ): ?>
		<?php $dataID = $d[ $this->rowIDFieldName ]; ?>
		<?php $rowID = $this->rowIDPrefix . $dataID; ?>
		<?php //$rowClass = isset( $this->activeRows[ $dataID ] ) ? 'active' : 'inactive'; ?>
		<?php $rowClass = $this-> rowClasses-> getRowClasses( $d ); ?>
		<tr id='<?php echo $rowID; ?>' class='<?php echo $rowClass; ?>'>
			<th scope='row' class='check-column'>
				<?php if ( $this-> includeCheckboxes ): ?>
				<input type='checkbox' name='checked[]' value='<?php echo $dataID; ?>' id='checkbox_<?php echo $dataID; ?>' />
				<?php endif; ?>
			</th>
			<?php $this-> renderRow( $d ); ?>
		</tr>		
		<?php endforeach; ?>
	</tbody>

	<tfoot>
	<tr>
		<td  id='cb' class='manage-column column-cb check-column'>
			<?php if ( $this-> includeCheckboxes ): ?>
			<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
			<input id="cb-select-all-1" type="checkbox" />
			<?php endif; ?>
		</td>
		<?php foreach ( $this-> cols as $col ): ?>
			<?php if ( !$col->_hideColumn ): ?>
				<?php $classString = 'manage-column ' . $col->getClassString(); ?>			
				<th scope="col" id='<?php echo htmlspecialchars($col->id); ?>' class='<?php echo $classString; ?>'><?php echo htmlspecialchars($col->title); ?></th>
			<?php endif; ?>
		<?php endforeach; ?>
	</tr>
	</tfoot>

</table>
<?php
	}
	
	function renderInputForm( $d = array(), $actionURL = "", $hiddenVars = array() ){
		$rowID = isset( $d[ $this->rowIDFieldName ] ) ? $d[ $this->rowIDFieldName ] : '';
?>
	<form id="<?php echo $this-> formID; ?>" method="post" action="<?php echo htmlspecialchars($actionURL); ?>" >
<table class="form-table">
	<?php foreach ( $this-> cols as $col ): ?>
	<?php if ( $col->hasInput() !== false ): ?>
	<tr>
	<th scope="row"><label for="<?php echo $col->id; ?>"><?php echo $col->title; ?></label></th>
	<td><?php $col->renderInput( $rowID, $d ); ?></td>
	</tr>
	<?php endif; ?>
	<?php endforeach; ?>
</table>
	<?php foreach ( $hiddenVars as $name => $value ): ?>
	<input type="hidden" name="<?php echo $name; ?>" value="<?php echo $value; ?>" />
	<input type="hidden" name="crud-row-id" value="<?php echo $rowID; ?>" />
	<?php endforeach; ?>
	<?php $this-> renderSubmitButton( $this-> submitText ); ?>

</form>
<?php
	}
	
	function renderSubmitButton( $text ){
?>
<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo $text; ?>"  /></p>
<?php
	}
}