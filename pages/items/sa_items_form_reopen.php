<?php
class SA_Form_ReopenItem
{
	var $itemID;
	var $action;
	
	function __construct( $itemID, $action = '' ){
		$this-> itemID = $itemID;
		$this-> action = $action;
	}
	
	function renderForm(){
		global $SA_Tables;
		$itemInfo = $SA_Tables-> items-> getByID( $this-> itemID );
		
		?>
<form id="reopen-item" method="post" action="<?php echo $this-> action; ?>" >
	<table class="form-table">
		
		<tr>
		<th scope="row"><label for="title">Title</label></th>
		<td><?php echo htmlspecialchars( $itemInfo[ 'title' ] ); ?></td>
		</tr>
		
		<tr>
		<th scope="row"><label for="description">Description</label></th>
		<td><?php echo htmlspecialchars( $itemInfo[ 'description' ] ); ?></td>
		</tr>
		
		<tr>
		<th scope="row"><label for="winningBidderID">Winning Bidder #</label></th>
		<td><?php echo htmlspecialchars( $itemInfo[ 'winningBidderID' ] ); ?></td>
		</tr>
		
		<tr>
		<th scope="row"><label for="winningBid">Winning Bid</label></th>
		<td><?php echo htmlspecialchars( $itemInfo[ 'winningBid' ] ); ?></td>
		</tr>
		
		<tr>
		<th scope="row"><label for="paid">Paid</label></th>
		<td><?php echo ( $itemInfo[ 'paid' ] == 1 ? 'yes' : 'no' ); ?></td>
		</tr>
		
	</table>
	
	<input type="hidden" name="action-reopen-submit" value="1" />
	<input type="hidden" name="id" value="<?php echo $this->itemID; ?>" />	
	<p class="submit">
		<input type="submit" name="submit" id="submit" class="button button-primary" value="Confirm Reopen Item"  />
	</p>
	
</form>		
		<?php
	}
	
	function processFormPost(){
		global $SA_Tables;
		
		$SA_Tables-> items-> reopen( $_POST[ 'id' ] );
	}
}
?>