<?php 

function sa_template_Summary( $currentEventInfo, $bidderInfo, $tableNumber, $auctionSections ){
	$bidderTotal = 0;
	
	ob_start(); ?>
	<div style="font-family:Arial;">
		
		<h1>MOEDA Gala Guest Summary</h1>
		
		<table class="form-table" width="100%" >	

		<tr>
		<td scope="row"><?php echo htmlspecialchars( $bidderInfo[ 'name' ] ); ?></td>
		<td>&nbsp;</td>
		</tr>		
		
		<tr>
		<td scope="row" colspan="2">&nbsp;</td>
		</tr>
		
		<tr>
		<td scope="row">
			<label for="name"><strong>Bidder Number:</strong></label>
			<?php echo htmlspecialchars( $bidderInfo[ 'bidderNumber' ] ); ?>
		</td>
		<td>
			<label for="name"><strong>Table Number:</strong></label>
			<?php echo htmlspecialchars( $tableNumber ); ?>
		</td>
		</tr>		
		
		<tr>
		<td scope="row" colspan="2"><hr /></td>
		</tr>
		
		<tr>
		<td scope="row">
			<label for="name"><strong>Item</strong></label>			
		</td>
		<td>
			<label for="name"><strong>Amount</strong></label>			
		</td>
		</tr>		

		<?php foreach ( $auctionSections as $sect ): ?>			
			<?php foreach ( $sect[ 'items' ] as $item ): ?>
				<?php $bidderTotal += $item[ 'winningBid' ]; ?>
				<tr>
				<td scope="row"><label for="name"><i><?php echo $item[ 'title' ]; ?></i></label></td>
				<td>
					<p><?php echo sprintf( "$%.2f", $item[ 'winningBid' ] ); ?></p>
				</td>
				</tr>
			<?php endforeach; ?>
			
			<tr>
			<td scope="row" colspan="2">&nbsp;</td>
			</tr>
			
		<?php endforeach; ?>
		<tr>
		<td scope="row" colspan="2">&nbsp;</td>
		</tr>
		
		<tr>
		<td scope="row"><label for="total"><strong>Total</strong></label></td>
		<td><?php echo sprintf( "$%.2f", $bidderTotal ); ?></td>
		</tr>

		<tr>
		<td scope="row" colspan="2">&nbsp;</td>
		</tr>
		
		<tr>
		<td scope="row" colspan="2">&nbsp;</td>
		</tr>
		
		</table>
		
		<div style="font-size:12px;">
		<p>
			PAYMENT METHOD:			
		</p>
		<p>
		____CASH<br />
		____CHECK NUMBER_____<br />
		____CREDIT CARD
		</p>
		</div>
		
	</div>
<?php 
	return ob_get_clean();		
}

?>