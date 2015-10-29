<?php


class SA_Form_BidderSummary
{
	var $bidderID;
	var $action;
	
	function __construct( $bidderID, $bidderNumber, $action = '' ){
		$this-> bidderID = $bidderID;
		$this-> bidderNumber = $bidderNumber;
		$this-> action = $action;
	}
	
	function renderForm(){
		global $SA_Tables;
		
		global $SA_DIR;
		require( $SA_DIR . "/mpdf60/mpdf.php" );

		$currentEventID = get_option( 'sa-current-event', '' );
		
		$bidderInfo = $SA_Tables-> getBidderInfo( $currentEventID, $this-> bidderID );
		$auctionItems = $SA_Tables-> items-> getWinningBidsByBidderNumber( $this-> bidderNumber );
		$bidderTotal = 0;
		
	ob_start(); ?>
	
		<h1>Bidder Summary</h1>
		<table class="form-table" width="100%">	

		<tr>
		<td scope="row"><label for="name"><strong>Bidder #</strong></label></td>
		<td><?php echo htmlspecialchars( $bidderInfo[ 'bidderNumber' ] ); ?></td>
		</tr>

		<tr>
		<td scope="row"><label for="name"><strong>Name</strong></label></td>
		<td><?php echo htmlspecialchars( $bidderInfo[ 'name' ] ); ?></td>
		</tr>
		
		<tr>
		<td scope="row" colspan="2"><hr /></td>
		</tr>

		<?php foreach ( $auctionItems as $item ): ?>
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
		
		<tr>
		<td scope="row"><label for="total"><strong>Total</strong></label></td>
		<td><?php echo sprintf( "$%.2f", $bidderTotal ); ?></td>
		</tr>

		</table>
<?php 
	$ob = ob_get_clean();
		
		$pdf = new mPDF();	
		$pdf->WriteHTML( $ob );
		$docStr = $pdf->Output( "doc.pdf", "S" );

		?>
		<div style="width:100%;height:600px;">
		<embed width=100% height=100%type="application/pdf" src="data:application/pdf;base64,<?php echo base64_encode($docStr); ?>"></embed>
		</div>
		<?php
		
		// echo $ob;
	}
	
	function processFormPost(){
		
	}
}
?>