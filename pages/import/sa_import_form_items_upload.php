<?php
class SA_Form_ItemsUpload
{
	function __construct( $action, $postKey ){
		$this->action = $action;
		$this->postKey = $postKey;
	}
	
	function renderForm(){
		?>
<form method="post" enctype="multipart/form-data" action="<?php echo $this-> action; ?>" >
	<table class="form-table">
		<tr>
		<th scope="row"><label for="file-upload">File Upload</label></th>
		<td><input type="file" name="file-upload" id="file-upload"></td>
		</tr>
	</table>
	<input type="hidden" name="<?php echo $this->postKey ?>" value="1" />
	
	<p class="submit">
		<input type="submit" name="submit" id="submit" class="button button-primary" value="Upload"  />
	</p>	
</form>		
		<?php
	}
	
	function processPost(){
		$filename = $_FILES[ 'file-upload' ][ "tmp_name" ];
		
		$objPHPExcel = PHPExcel_IOFactory::load($filename);
		
		$objWorksheet = $objPHPExcel->getSheet(0);
		
		// Get the highest row and column numbers referenced in the worksheet
		$highestRow = $objWorksheet->getHighestRow(); // e.g. 10
		$highestColumn = $objWorksheet->getHighestColumn(); // e.g 'F'
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); // e.g. 5

		// decode column names
		$columnNamesToKeys = array();
		
		
		/*
		Bidders - 
		
		Table #
		Type 
		Table
		Bid No.
		Full Name
		Mailing Names
		#
		Address
		City
		State
		Zip
		Email

		Items -
		
		Contact Name
		Business
		Address
		City
		State
		Zip
		Description
		Value
		Notes

		*/
		
		echo '<table>' . "\n";
		for ($row = 1; $row <= $highestRow; ++$row) {
			echo '<tr>' . PHP_EOL;
			for ($col = 0; $col <= $highestColumnIndex; ++$col) {
				echo '<td>' . 
					 $objWorksheet->getCellByColumnAndRow($col, $row)
						 ->getValue() . 
					 '</td>' . PHP_EOL;
			}
			echo '</tr>' . PHP_EOL;
		}
		echo '</table>' . PHP_EOL;
	}
}