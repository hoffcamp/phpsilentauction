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
	
	$maxBidLines = 30;
	
	$showPage = ( $currentEventID != '' );

	$data = array();
	foreach ( $sectionList as $sect ){
		if ( isset( $_POST[ 'section-' . $sect[ 'ID' ] ] ) ){
			$data = array_merge( $data, $SA_Tables-> items-> getAll( $currentEventID, $sect[ 'ID' ], true ) );
		}
	}
	
	/////////////////////////////
	// Etc.
		
	$pieces = array();
	$maxIndex = 0;
	foreach ( $data as $index => $d ){
		$contact = $SA_Tables-> contacts-> getByID( $d[ 'contactID' ] );
		
		$minBid = $d[ 'startBid' ];
		$value = $d[ 'value' ];		
		
		// make sure minincrease is sane
		$minIncrease = $d[ 'minIncrease' ];
		if ( $minIncrease == 0 ){
			$minIncrease = $value / 20;
		}
		
		$maxBid = $minBid + ( $minIncrease * 20 );
		
		ob_start();
?>
		<h2><?php echo $d[ 'title' ]; ?></h2>
		<div style="width:50%">
		Donated by
		<h3><?php echo $contact[ 'business' ]; ?></h3>
		<br />
		<p><?php echo $d[ 'description' ]; ?></p>
		<br />
		<?php 
		$counter = 0;
		for ( $bid = $minBid; $bid <= $maxBid && $counter < $maxBidLines; $bid += $minIncrease ): ?>
		<?php $counter++; ?>
		<table width="100%"><tr><td width="300px"><hr/></td><td width="100px"><?php echo sprintf( "$%.2f", $bid ); ?></td></tr></table>
		<?php endfor; ?>
		</div>
<?php
		$pieces[ $maxIndex ] = ob_get_clean();
		$maxIndex ++;		
	}
	
/////////////////////////////
ob_start();
?>
<?php for ( $i=0; $i<$maxIndex; $i++ ): ?>
<table style="width:100%">	
	<tr>
		<td style="width:50%;padding:15px;vertical-align:top;"><?php echo $pieces[$i]; ?></td>	
		<?php $i++; if ( $i == $maxIndex ){ $i--; } ?>	
		<td style="width:50%;padding:15px;vertical-align:top;"><?php echo $pieces[$i]; ?></td>	
	</tr>
</table>
<pagebreak />
<?php endfor; ?>
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
		"Lot #", "Bidder #", "Winning Bid", "Value", "%Value"
	);
	
	// pretend we did a proper join on bidder, contact ID
	foreach ( $data as $d ){
		if ( $d[ 'winningBidderID' ] != 0 ){
			$value = $d[ 'value' ];
			$pctValue = $d[ 'winningBid' ] / $value;
			
			$exportData[] = array(
				$d[ 'lotID' ], $d[ 'winningBidderID' ],
				sprintf( "$%.2f", $d[ 'winningBid' ] ),
				sprintf( "$%.2f", $value ),
				sprintf( "%.2f %%", $pctValue * 100 )
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
	?>
	<form method="post" action="<?php echo get_admin_url(null, 'admin.php')."?page=sa-export"; ?>" >
	<p>
		<?php foreach ( $sectionList as $sect ): ?>
			<label for="section-<?php echo $sect['ID']; ?>">
			<?php echo $sect['title']; ?>:&nbsp;
			<?php $sectionID = 'section-'.$sect['ID']; ?>
			<input type="checkbox" name="<?php echo $sectionID; ?>"  <?php if ( isset( $_POST[$sectionID] ) ){ echo 'checked'; } ?> />
			</label>
		<?php endforeach; ?>
	</p>
	
	<select name="export-type">
	<?php $types = array( 
		'bidsheets' => "Bid Sheets",
		'auctionlog' => "Auction Log",
		'bidderlog' => "Bidder Log",
		'donorlog' => "Donor Log" );
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
		}
	}
}
?>
</div>