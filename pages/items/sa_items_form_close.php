<?php
class SA_Form_CloseItem
{
	var $itemID;
	var $action;
	
	function __construct( $itemID, $action = '' ){
		$this-> itemID = $itemID;
		$this-> action = $action;
	}
	
	function renderForm(){
		?>
<form id="close-item" method="post" action="<?php echo $this-> action; ?>" >
	<table class="form-table">
		
		<tr>
		<th scope="row"><label for="winningBidderID">Winning Bidder #</label></th>
		<td><input type="text" class="regular-text" name="winningBidderID" value=""/></td>
		</tr>
		
		<tr>
		<th scope="row"><label for="winningBidderID">Winning Bid</label></th>
		<td><input type="text" class="regular-text" name="winningBid" value=""/></td>
		</tr>
		
	</table>
	<input type="hidden" name="action-close-submit" value="1" />
	<input type="hidden" name="id" value="<?php echo $this->itemID; ?>" />
	
	<p class="submit">
		<input type="submit" name="submit" id="submit" class="button button-primary" value="Close Item"  />
	</p>
	
</form>		
		<?php
	}
	
	function processFormPost(){
		global $SA_Tables;
		
		$SA_Tables-> items-> close( $_POST[ 'id' ], $_POST[ 'winningBidderID' ], $_POST[ 'winningBid' ] );
	}
}
?>