<?php

function sa_template_BidSheet( $data ){
	global $SA_Tables;
	
	$currentEventID = get_option( 'sa-current-event' , '' );
	$sectionList = $SA_Tables-> itemSections-> getAll( $currentEventID );
	
	$maxBidLines = 32;
	$maxBidLines2 = 42;
	
	
	
	// generate the RHS template
	ob_start();		
		?>
		<table width="100%">
			<tr>	
			<tr>
				<td width="300px"><strong>Bidder #<strong></td>
				<td width="100px"><strong>Amount ($)</strong></td>
			</tr>
		
			
			<?php for( $i=1; $i<$maxBidLines2; $i++ ): ?>
				<tr>
					<td width="300px"><hr/></td>
					<td width="100px"><hr/></td>
				</tr>
			<?php endfor; ?>
		</table>		
		</div>
<?php		
	$pieceExtra = ob_get_clean();
	
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
		
		$valueStr =( $d['value'] == 0 )?
					'priceless'
					: sprintf( "$%.2f", $d[ 'value' ] );
		
		ob_start();
		
?>
		
		<table width="100%">
			<tr>
				<td width="200px" style="vertical-align:top;">
					<h3>No. <?php echo $d[ 'lotID' ]; ?></h3>
				</td>
				<td align="right" style="vertical-align:top;" >
					<h3>VALUE <?php echo $valueStr; ?></h3>
				</td>
				<tr>				
				<td>&nbsp;</td>
			</tr>
			</tr>
		</table>
		<table>
			<tr>
				<td width="400px" style="vertical-align:top;" >
					<strong><?php echo $d[ 'title' ]; ?></strong>
				</td>				
			</tr>
			<tr>				
				<td>&nbsp;</td>
			</tr>
		</table>
		
		<div style="width:50%">
		Donated by
		<strong><?php echo $contact[ 'name' ]; ?></strong>
		<br />
		<br />
		
		<table width="100%">
			<tr>
				<td width="300px">
				<p><?php echo $d[ 'description' ]; ?></p>
				</td>
				<td>&nbsp;</td>
			</tr>
		</table>
		
		
		<br />
		<table width="100%">
			<tr>
				<td width="400px">
				<strong>
				MINIMUM BID: <?php echo sprintf( "$%.2f", $d[ 'startBid' ] ); ?>
				&nbsp; 
				RAISE: <?php echo sprintf( "$%.2f", $d[ 'minIncrease' ] ); ?>
				</strong>
				</td>
			</tr>
			
			<tr>				
				<td>&nbsp;</td>
			</tr>
		</table>
		
		<table width="100%">
			<tr>	
			<tr>
				<td width="300px"><strong>Bidder #<strong></td>
				<td width="100px"><strong>Amount ($)</strong></td>
			</tr>
			
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			
			<?php for( $i=1; $i<$maxBidLines; $i++ ): ?>
				<tr>
					<td width="300px"><hr/></td>
					<td width="100px"><hr/></td>
				</tr>
			<?php endfor; ?>
		</table>
		
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
		<?php /* $i++; if ( $i == $maxIndex ){ $i--; } ?>	*/ ?>
		<td style="width:50%;padding:15px;vertical-align:top;"><?php echo $pieceExtra; ?></td>
	</tr>
</table>
<pagebreak />
<?php endfor; ?>
<?php

	return ob_get_clean();
	
/////////////////////////////
}?>