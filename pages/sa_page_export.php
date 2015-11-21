<?php

global $SA_DIR;
require( $SA_DIR . "/mpdf60/mpdf.php" );

// helpers
function writeCSVAndEchoNotice( $exportData, $filename, $successMessage, $failMessage ){
	global $SA_DIR;
	$fp = fopen( $SA_DIR . '/' . $filename, 'w');
	if ( $fp !== false ){
		foreach ( $exportData as $d ){
			fputcsv( $fp, $d );
		}
		fclose( $fp );
		
		echo '<div id="message" class="updated">';
		echo "<p>" . $successMessage . "</p>";
		echo '<p><strong><a href="' . plugins_url( 'silentauction' ) . '/' . $filename . '" >Download ('.$filename.')</a></strong></p>';
		echo '</div>';		
		
	} else {
		echo '<div id="message" class="error">';
		echo "<p>" . $failMessage . "</p>";
		echo '</div>';
	}	
}

////////////////////////////////////////////////////////////////////////////////////////////
// Bid Sheets

function doExportBidSheets(){
	global $SA_Tables;
	
	$currentEventID = get_option( 'sa-current-event' , '' );
	$sectionList = $SA_Tables-> itemSections-> getAll( $currentEventID );
	
	$maxBidLines = 32;
	$maxBidLines2 = 42;
	
	$showPage = ( $currentEventID != '' );

	$data = array();
	foreach ( $sectionList as $sect ){
		if ( isset( $_POST[ 'section-' . $sect[ 'ID' ] ] ) ){
			$data = array_merge( $data, $SA_Tables-> items-> getAll( $currentEventID, $sect[ 'ID' ], true ) );
		}
	}
	
	$ob = sa_template_BidSheet( $data );	
	
	$pdf = new mPDF();	
	$pdf->WriteHTML( $ob );
	$docStr = $pdf->Output( "doc.pdf", "S" );

	?>
	<div style="width:100%;height:600px;">
	<embed width=100% height=100%type="application/pdf" src="data:application/pdf;base64,<?php echo base64_encode($docStr); ?>"></embed>
	</div>
	<?php

}

////////////////////////////////////////////////////////////////////////////////////////////
// NameList

function doNameList(){
	global $SA_Tables;
	
	$currentEventID = get_option( 'sa-current-event' , '' );
	$bidderList = $SA_Tables-> getBidderList( $currentEventID );
	
	////////////////////////////
ob_start();
?>
<table style="width:100%">	
	<tr>
		<td><strong>Bidder #</strong></td>
		<td><strong>Name</strong></td>
	</tr>
<?php foreach ( $bidderList as $bidder ): ?>
	<tr>
		<td><?php echo $bidder[ 'bidderNumber' ]; ?></td>
		<td><?php echo $bidder[ 'name' ]; ?></td>
	</tr>
<?php endforeach; ?>
</table>
<?php
$ob = ob_get_clean();
/////////////////////////////
	
	$pdf = new mPDF();	
	$pdf->WriteHTML( $ob );
	$docStr = $pdf->Output( "doc.pdf", "S" );

	?>
	<div style="width:100%;height:600px;">
	<embed width=100% height=100%type="application/pdf" src="data:application/pdf;base64,<?php echo base64_encode($docStr); ?>"></embed>
	</div>
	<?php

}

////////////////////////////////////////////////////////////////////////////////////////////
// Auction Log

function doAuctionLog(){
	global $SA_Tables;
	global $SA_DIR;
	
	$currentEventID = get_option( 'sa-current-event' , '' );
	$sectionList = $SA_Tables-> itemSections-> getAll( $currentEventID );
	
	$maxBidLines = 30;
	
	$showPage = ( $currentEventID != '' );

	$data = array();
	
	foreach ( $sectionList as $sect ){
		if ( isset( $_POST[ 'section-' . $sect['ID'] ] ) ){
			$data = array_merge( $data, $SA_Tables-> items-> getAll( $currentEventID, $sect['ID'], true ) );
		}
	}	
	$exportData = array();
	
	$exportData[] = array(
		"Lot #", "Bidder #", "Winning Bid", "Value", "%Value", "Description"
	);
	
	// pretend we did a proper join on bidder, contact ID
	foreach ( $data as $d ){
		if ( $d[ 'winningBidderID' ] != 0 ){
			$value = $d[ 'value' ];
			
			$pctValue = ( $value == 0.0 ) ? 0.0 : ( $d[ 'winningBid' ] / $value );
			
			$exportData[] = array(
				$d[ 'lotID' ], $d[ 'winningBidderID' ],
				sprintf( "$%.2f", $d[ 'winningBid' ] ),
				sprintf( "$%.2f", $value ),
				sprintf( "%.2f %%", $pctValue * 100 ),
				$d[ 'title' ]
				);
		}
	}
	
	writeCSVAndEchoNotice( $exportData, 'auctionlog.csv', "Export successful.", "Unable to write auctionlog.csv" );
}

////////////////////////////////////////////////////////////////////////////////////////////
// Bidder Log ( every item won + bidder information )

function doBidderLog(){
	global $SA_Tables;
	
	$currentEventID = get_option( 'sa-current-event' , '' );
	$sectionList = $SA_Tables-> itemSections-> getAll( $currentEventID );
	
	$maxBidLines = 30;
	
	$showPage = ( $currentEventID != '' );

	$data = array();
	foreach ( $sectionList as $sect ){
		if ( isset( $_POST[ 'section-' . $sect['ID'] ] ) ){
			$data = array_merge( $data, $SA_Tables-> items-> getAll( $currentEventID, $sect['ID'], true ) );
		}
	}
	
	$exportData = array();	
	
	$exportData[] = array(
		"Item #", "Bidder #", "Winning Bid",
		"Item Description",		
		"Bidder Name", "Business", "Address", "City", "State", "Zip", "E-mail"		
	);
	
	// pretend we did a proper join on bidder, contact ID
	foreach ( $data as $d ){
		$contact = false;
		$bidderInfo = false;
		if ( $d[ 'winningBidderID' ] != 0 ){			
			$bidderInfo = $SA_Tables-> bidders-> getByBidderNumber( $d[ 'winningBidderID' ] );
			$contact = $SA_Tables-> contacts-> getByID( $bidderInfo[ 'contactID' ] );
			$exportData[] = array(
				$d[ 'ID' ], $d[ 'winningBidderID' ], sprintf( "$%.2f", $d[ 'winningBid' ] ),
				$d[ 'title' ],
				$contact[ 'name' ], $contact[ 'business' ], $contact[ 'addr' ], $contact[ 'city' ], $contact[ 'state' ], $contact[ 'zip' ], $contact[ 'email' ]
				);	
		}			
	}
	
	writeCSVAndEchoNotice( $exportData, 'bidderlog.csv', "Export successful.", "Unable to write bidderlog.csv" );
}

////////////////////////////////////////////////////////////////////////////////////////////
// Donor Log ( every item won + donor information )

function doDonorLog(){
	global $SA_Tables;
	
	$currentEventID = get_option( 'sa-current-event' , '' );
	$sectionList = $SA_Tables-> itemSections-> getAll( $currentEventID );
	
	$maxBidLines = 30;
	
	$showPage = ( $currentEventID != '' );

	$data = array();
	foreach ( $sectionList as $sect ){
		if ( isset( $_POST[ 'section-' . $sect['ID'] ] ) ){
			$data = array_merge( $data, $SA_Tables-> items-> getAll( $currentEventID, $sect['ID'], true ) );
		}
	}
	
	$exportData = array();	
	
	$exportData[] = array(
		"Item #", "Bidder #", "Winning Bid",
		"Item Description",		
		"Donor Name", "Business", "Address", "City", "State", "Zip", "E-mail"		
	);
	
	// pretend we did a proper join on bidder, contact ID
	foreach ( $data as $d ){
		$contact = false;
		$bidderInfo = false;
		if ( $d[ 'winningBidderID' ] != 0 ){						
			$contact = $SA_Tables-> contacts-> getByID( $d[ 'contactID' ] );
			$exportData[] = array(
				$d[ 'ID' ], $d[ 'winningBidderID' ], sprintf( "$%.2f", $d[ 'winningBid' ] ),
				$d[ 'title' ],
				$contact[ 'name' ], $contact[ 'business' ], $contact[ 'addr' ], $contact[ 'city' ], $contact[ 'state' ], $contact[ 'zip' ], $contact[ 'email' ]
				);	
		}			
	}
	
	writeCSVAndEchoNotice( $exportData, 'donorlog.csv', "Export successful.", "Unable to write donorlog.csv" );
}

?>
<div class="wrap">
<?php

$currentEventID = get_option( 'sa-current-event' , '' );
$showPage = ( $currentEventID != '' );
	
$subtitle = __( "Export", 'silentauction' );
sa_heading( $subtitle );

global $SA_Tables;
$sectionList = $SA_Tables-> itemSections-> getAll( $currentEventID );

if ( $showPage ){
	$sectionChecked = array();
	$isPost = isset( $_POST[ 'sa-export-form' ] );	
	
	foreach ( $sectionList as $sect ){
		$sectionChecked[ $sect['ID'] ] =
		( $isPost == false )
			|| ( isset( $_POST[ 'section-'.$sect['ID'] ] ) );
	}
	
	?>
	<form method="post" action="<?php echo get_admin_url(null, 'admin.php')."?page=sa-export"; ?>" >
	
	<input type="hidden" name="sa-export-form" value="1" />
	
	<?php $usePost = isset( $_POST[ 'sa-export-form' ] ); ?>
	
	<p>
		<?php foreach ( $sectionList as $sect ): ?>
			<label for="section-<?php echo $sect['ID']; ?>">
			<?php echo $sect['title']; ?>:&nbsp;
			<?php $sectionID = 'section-'.$sect['ID']; ?>
			<input type="checkbox" name="<?php echo $sectionID; ?>"  <?php if ( $sectionChecked[ $sect['ID'] ] ){ echo 'checked'; } ?> />
			</label>
		<?php endforeach; ?>
	</p>
	
	<select name="export-type">
	<?php $types = array( 
		'bidsheets' => "Bid Sheets",
		'auctionlog' => "Auction Log",
		'bidderlog' => "Bidder Log",
		'donorlog' => "Donor Log",
		'namelist' => "Name List" );
		foreach ( $types as $t => $title ): ?>
			<option value="<?php echo $t; ?>" <?php 
				if ( isset( $_POST[ 'export-type' ] ) && $_POST[ 'export-type' ] == $t ){ echo 'selected="selected"'; }
				?>
			><?php echo $title; ?></option>			
	<?php endforeach; ?>
	</select>
	
	<p class="submit">
		<input type="submit" class="button-primary" name="submit" value="Export" />
	</p>
	
	</form>
	</hr />
	<?php
	
	if ( isset( $_POST[ 'export-type' ] ) ){
		switch( $_POST[ 'export-type' ] ){
		case "bidsheets":
			doExportBidSheets();
			break;
		case "auctionlog":
			doAuctionLog();
			break;
		case "bidderlog":
			doBidderLog();
			break;
		case "donorlog":
			doDonorLog();
			break;
		case "namelist":
			doNameList();
			break;
		}
	}
}
?>
</div>