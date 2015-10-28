<?php

global $SA_DIR;
require( $SA_DIR . "/mpdf60/mpdf.php" );

////////////////////////////////////////////////////////////////////////////////////////////
// Bid Sheets

function doExportBidSheets(){
	global $SA_Tables;
	
	$currentEventID = get_option( 'sa-current-event' , '' );
	$showPage = ( $currentEventID != '' );

	$data =  $SA_Tables-> items-> getAll( $currentEventID, true );
	
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
		<?php for ( $bid = $minBid; $bid <= $maxBid; $bid += $minIncrease ): ?>		
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

?>
<div class="wrap">
<?php

$currentEventID = get_option( 'sa-current-event' , '' );
$showPage = ( $currentEventID != '' );
	
$subtitle = __( "Export", 'silentauction' );
sa_heading( $subtitle );

if ( $showPage ){
	if ( isset( $_GET[ 'item' ] ) ){
		switch( $_GET[ 'item' ] ){
		case "bidsheets":
			doExportBidSheets();
			break;
		}
	} else {			
		?>
		<p class="submit">
			<a href="<?php echo get_admin_url(null, 'admin.php')."?page=sa-export&item=bidsheets";?>" class="button button-primary">Bid Sheets</a>
		</p>
		<?php
	}
}
?>
</div>