<?php
class SA_Form_BiddersUpload
{
	function __construct( $action ){
		$this->action = $action;		
	}
	
	function renderForm( $postKey ){
		?>
<form method="post" enctype="multipart/form-data" action="<?php echo $this-> action; ?>" >
	<table class="form-table">
		<tr>
		<th scope="row"><label for="file-upload">Import Bidders</label></th>
		<td><input type="file" name="file-upload" id="file-upload"></td>
		</tr>
	</table>
	<input type="hidden" name="<?php echo $postKey ?>" value="1" />
	
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
			"Full Name" => 'name',		
			"Address" => 'addr',
			"City" => 'city',
			"State" => 'state',
			"Zip" => 'zip',			
			"Email" => 'email',
			"Bid No." => 'bidderNumber'
		);
		
		$columnIndices = array();
		
		for ( $col = 0; $col <= $highestColumnIndex; $col++ ){
			$colValue = $objWorksheet->getCellByColumnAndRow($col, 1)->getValue();			
			if ( isset( $columnNamesToKeys[ $colValue ] ) ){
				$colKey = $columnNamesToKeys[ $colValue ];
				$columnIndices[ $col ] = $colKey;				
			}
		}
		
		$data = array();		
		
		for ($row = 2; $row <= $highestRow; ++$row) {
			$d = array(				
				'name' => '',
				'business' => '',
				'addr' => '',
				'city' => '',
				'state' => '',
				'zip' => '',
				'email' => '',
				'bidderNumber' => ''
			);
			$hasValue = false;
			for ( $col = 0; $col <= $highestColumnIndex; $col++ ){
				$cellValue = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();				
				if ( isset( $columnIndices[ $col ] ) ){
					if ( trim($cellValue) != "" ){ $hasValue = true; }
					$d[ $columnIndices[ $col ] ] = $cellValue;
				}
			}
			if ( $hasValue ){
				$data[] = $d;
			}
		}		

		return $data;
	}

	function verifyData( $data, $postKey ){
		?>
		<table>
			<thead>
				<tr>
					<th><?php _e( "Full Name", 'silentauction' ); ?></th>
					<th><?php _e( "Bid No.", 'silentauction' ); ?></th>
					<th><?php _e( "Email", 'silentauction' ); ?></th>					
					<th><?php _e( "Address", 'silentauction' ); ?></th>
					<th><?php _e( "City", 'silentauction' ); ?></th>
					<th><?php _e( "State", 'silentauction' ); ?></th>
					<th><?php _e( "ZIP", 'silentauction' ); ?></th>					
				</tr>
			</thead>
			<?php foreach( $data as $d ): ?>
				<tr>
					<td><?php echo $d[ 'name' ]; ?></td>
					<td><?php echo $d[ 'bidderNumber' ]; ?></td>
					<td><?php echo $d[ 'email' ]; ?></td>					
					<td><?php echo $d[ 'addr' ]; ?></td>
					<td><?php echo $d[ 'city' ]; ?></td>
					<td><?php echo $d[ 'state' ]; ?></td>
					<td><?php echo $d[ 'zip' ]; ?></td>					
				</tr>
			<?php endforeach; ?>
			<tfoot>
				<tr>
					<th><?php _e( "Full Name", 'silentauction' ); ?></th>
					<th><?php _e( "Bid No.", 'silentauction' ); ?></th>
					<th><?php _e( "Email", 'silentauction' ); ?></th>					
					<th><?php _e( "Address", 'silentauction' ); ?></th>
					<th><?php _e( "City", 'silentauction' ); ?></th>
					<th><?php _e( "State", 'silentauction' ); ?></th>
					<th><?php _e( "ZIP", 'silentauction' ); ?></th>					
				</tr>
			</tfoot>
		</table>
		<form method="post" enctype="multipart/form-data" action="<?php echo $this-> action; ?>" >			
			<input type="hidden" name="<?php echo $postKey ?>" value="1" />			
			<p class="submit">
				<input type="submit" name="submit" id="submit" class="button button-primary" value="Import Data"  />
			</p>	
		</form>	
		<?php		
	}
}