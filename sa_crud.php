<?php

class SA_CRUD_Column
{
	var $title;
	var $id;
	var $classes;
	
	function __construct( $id = '', $title = '' ){
		$this-> id = $id;
		$this-> title = $title;
	}
	
	function addClass( $className ){ $this-> classes[ $className ] = $className; }	
	function removeClass( $className ){	unset( $this-> classes[ $className ] );	}	
	function getClassString(){ return implode( ' ', $this-> classes ); }
	function renderData( $d ){ echo $d[ $this-> id ]; }
	function getInputID(){ return 'input-' . $this->id; }
	function renderInput( $d ){
		$value = isset( $d[ $this->id ] ) ? $d[ $this-> id ] : '';
		$id = $this->getInputID();
		echo "<input type=\"text\" name=\"{$id}\" id=\"{$id}\" value=\"{$value}\" class=\"regular-text\"/>";
	}
	function getInputValue(){
		return stripslashes( $_POST[ 'input-' . $this->id ] );
	}
}

class SA_CRUD_EmptyColumn extends SA_CRUD_Column
{
	function getInputID(){ return false; }
	function getInputValue(){ return false; }
	function renderInput( $d ){}
	function renderData( $d ){}
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
	
	function __construct( $formID = 'crud-form' ){		
		$this-> formID = $formID;
		$this-> includeCheckboxes = false;
		$this-> cols = array();
		$this-> rowIDFieldName = 'ID';
		$this-> rowIDPrefix = $formID . '-';
		$this-> activeRows = array();
		$this-> submitText = "Submit";
	}
	
	function col( SA_CRUD_Column $col ){ $this-> cols[] = $col; return $col; }	
	function activeRow( $rowID ){ $this-> activeRows[ $rowID ] = $rowID; }
	
	function processInputFormPost(){
		$d = array();
		foreach ( $this-> cols as $col ){
			if ( $col-> getInputID() !== false ){
				$d[ $col-> id ] = $col-> getInputValue();
			}
		}
		return $d;
	}
	
	function renderTable( $data ){
?>
<table class="wp-list-table widefat plugins">
	<thead>
	<tr>
		<td  id='cb' class='manage-column column-cb check-column'>
			<?php if ( $this-> includeCheckboxes ): ?>
			<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
			<input id="cb-select-all-1" type="checkbox" />
			<?php endif; ?>
		</td>
		<?php foreach ( $this-> cols as $col ): ?>
		<?php $classString = 'manage-column ' . $col->getClassString(); ?>
		<th scope="col" id='<?php echo htmlspecialchars($col->id); ?>' class='<?php echo $classString; ?>'><?php echo htmlspecialchars($col->title); ?></th>
		<?php endforeach; ?>
	</tr>
	</thead>

	<tbody id="the-list">
	
		<?php foreach ( $data as $d ): ?>
		<?php $dataID = $d[ $this->rowIDFieldName ]; ?>
		<?php $rowID = $this->rowIDPrefix . $dataID; ?>
		<?php $rowClass = isset( $this->activeRows[ $dataID ] ) ? 'active' : 'inactive'; ?>
		<tr id='<?php echo $rowID; ?>' class='<?php echo $rowClass; ?>'>
			<th scope='row' class='check-column'>
				<?php if ( $this-> includeCheckboxes ): ?>
				<input type='checkbox' name='checked[]' value='<?php echo $dataID; ?>' id='checkbox_<?php echo $dataID; ?>' />
				<?php endif; ?>
			</th>
			<?php foreach ( $this-> cols as $col ): ?>
			<?php $classString = $col->getClassString(); ?>
			<td class='<?php echo $classString; ?>'>
				<?php $col->renderData( $d ); ?>
			</td>
			<?php endforeach; ?>
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
		<?php $classString = 'manage-column ' . $col->getClassString(); ?>
		<th scope="col" id='<?php echo htmlspecialchars($col->id); ?>' class='<?php echo $classString; ?>'><?php echo htmlspecialchars($col->title); ?></th>
		<?php endforeach; ?>
	</tr>
	</tfoot>

</table>
<?php
	}
	
	function renderInputForm( $d = array(), $actionURL = "", $hiddenVars = array() ){
?>
	<form id="<?php echo $this-> formID; ?>" method="post" action="<?php echo htmlspecialchars($actionURL); ?>" >
<table class="form-table">
	<?php foreach ( $this-> cols as $col ): ?>
	<?php if ( $col->getInputID() !== false ): ?>
	<tr>
	<th scope="row"><label for="<?php echo $col->id; ?>"><?php echo $col->title; ?></label></th>
	<td><?php $col->renderInput( $d ); ?></td>
	</tr>
	<?php endif; ?>
	<?php endforeach; ?>
</table>
	<?php foreach ( $hiddenVars as $name => $value ): ?>
	<input type="hidden" name="<?php echo $name; ?>" value="<?php echo $value; ?>" />
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