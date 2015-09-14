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
	
	// return the data scraped from the document
	function processPost(){
		$filename = $_FILES[ 'file-upload' ][ "tmp_name" ];
		
		$objPHPExcel = PHPExcel_IOFactory::load($filename);
		
		$objWorksheet = $objPHPExcel->getSheet(0);
		
		// Get the highest row and column numbers referenced in the worksheet
		$highestRow = $objWorksheet->getHighestRow(); // e.g. 10
		$highestColumn = $objWorksheet->getHighestColumn(); // e.g 'F'
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); // e.g. 5

		// decode column names
		$columnNamesToKeys = array(	
			"Contact Name" => 'name',
			"Business" => 'business',
			"Address" => 'addr',
			"City" => 'city',
			"State" => 'state',
			"Zip" => 'zip',
			"Description" => 'description',
			"Value" => 'value'
		);		
		
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
		
		$columnIndices = array();
		
		for ( $col = 0; $col <= $highestColumnIndex; $col++ ){
			$colValue = $objWorksheet->getCellByColumnAndRow($col, 1)->getValue();			
			if ( isset( $columnNamesToKeys[ $colValue ] ) ){
				$colKey = $columnNamesToKeys[ $colValue ];
				$columnIndices[ $col ] = $colKey;
				echo "Mapping '" . $colValue . "' to `" . $colKey . "`<br/>";
			}
		}
		
		$data = array();
		
		for ($row = 2; $row <= $highestRow; ++$row) {
			$d = array(
				'title' => '',
				'description' => '',
				'value' => '',
				'startBid' => '',
				'minIncrease' => '',
				'name' => '',
				'business' => '',
				'addr' => '',
				'city' => '',
				'state' => '',
				'zip' => '',
				'email' => '',
			);
			for ( $col = 0; $col <= $highestColumnIndex; $col++ ){
				$cellValue = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
				if ( isset( $columnIndices[ $col ] ) ){
					$d[ $columnIndices[ $col ] ] = $cellValue;
				}
			}
			$data[] = $d;
		}		

		return $data;
	}
}