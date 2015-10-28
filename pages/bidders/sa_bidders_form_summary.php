<?php
class SA_Form_BidderSummary
{
	var $bidderID;
	var $action;
	
	function __construct( $bidderID, $action = '' ){
		$this-> bidderID = $bidderID;
		$this-> action = $action;
	}
	
	function renderForm(){
		global $SA_Tables;
		$currentEventID = get_option( 'sa-current-event', '' );
		
		$bidderInfo = $SA_Tables-> getBidderInfo( $currentEventID, $this-> bidderID );
		$auctionItems = $SA_Tables-> items-> getWinningBidsByBidderID( $this-> bidderID );
		$bidderTotal = 0;
		
		?>
<form id="bidder-summary" method="post" action="<?php echo $this-> action; ?>" >
	<table class="form-table">
	
		<tr>
		<th scope="row"><label for="name">Bidder #</label></th>
		<td><?php echo htmlspecialchars( $bidderInfo[ 'bidderNumber' ] ); ?></td>
		</tr>
	
		<tr>
		<th scope="row"><label for="name">Name</label></th>
		<td><?php echo htmlspecialchars( $bidderInfo[ 'name' ] ); ?></td>
		</tr>
	
		<?php foreach ( $auctionItems as $item ): ?>
			<?php $bidderTotal += $item[ 'winningBid' ]; ?>
			<tr>
			<th scope="row"><label for="name"><?php echo $item[ 'title' ]; ?></label></th>
			<td>
				<p><?php echo sprintf( "%.2f", $item[ 'winningBid' ] ); ?></p>
			</td>
			</tr>
		<?php endforeach; ?>
	
		<tr>
		<th scope="row"><label for="total"><strong>Total</strong></label></th>
		<td><?php echo sprintf( "%.2f", $bidderTotal ); ?></td>
		</tr>
	
	</table>
	
	<input type="hidden" name="id" value="<?php echo $this->bidderID; ?>" />
	<input type="hidden" name="action-payment" value="1" />

</form>
		<?php
	}
	
	function processFormPost(){
		
	}
}
?>