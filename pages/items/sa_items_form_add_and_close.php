<?php
class SA_Form_AddAndClose
{
	var $action;
	var $eventID;
	var $sectionID;
	var $defaultTitle;
	
	function __construct( $action = '', $eventID, $sectionID, $defaultTitle ){		
		$this-> action = $action;
		$this-> eventID = $eventID;
		$this-> sectionID = $sectionID;
		$this-> defaultTitle = $defaultTitle;
	}
	
	function renderForm(){
		?>
<form id="close-item" method="post" action="<?php echo $this-> action; ?>" >
	<table class="form-table">
		<tr>
		<th scope="row"><label for="winningBidderID">Description </label></th>
		<td><input type="text" class="regular-text" name="item-title" value="<?php echo htmlspecialchars($this-> defaultTitle ); ?>"/></td>
		</tr>	
		
		<tr>
		<th scope="row"><label for="winningBidderID">Bidder #</label></th>
		<td><input type="text" class="regular-text" name="winningBidderID" value=""/></td>
		</tr>
		
		<tr>
		<th scope="row"><label for="winningBidderID">Bid / Amount</label></th>
		<td><input type="text" class="regular-text" name="winningBid" value=""/></td>
		</tr>
		
	</table>
	<input type="hidden" name="action-add-and-close-submit" value="1" />	
	
	<p class="submit">
		<input type="submit" name="submit" id="submit" class="button button-primary" value="Close Item"  />
	</p>
	
</form>		
		<?php
	}
	
	function processFormPost(){
		global $SA_Tables;
		
		// insert a new item		
		$itemID = $SA_Tables-> items-> add(
			$this->eventID,
			$this->sectionID, 0, $_POST[ 'item-title' ], $_POST[ 'item-title' ], $_POST[ 'winningBid' ], $_POST[ 'winningBid' ], 1, 0 );
		
		// close the item
		$SA_Tables-> items-> close( $itemID, $_POST[ 'winningBidderID' ], $_POST[ 'winningBid' ] );
	}
	
}

?>