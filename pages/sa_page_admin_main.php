<?php
global $SA_Tables;
global $SA_AdminLinks;

?>
<div class="wrap">
<?php

sa_heading( __( "Summary", 'silentauction' ) ); 

// no event is currently set
$currentEventID = get_option( 'sa-current-event', '' );
$linkClassString = 'page-title-action';
$eventsPageKey = 'sa-events';
$biddersPageKey = 'sa-bidders';
$itemsPageKey = 'sa-items';

if ( $currentEventID == '' ){
	$eventList = $SA_Tables-> events-> getAll();

	// ... because there are no events
	if ( count( $eventList ) == 0 ){
		echo '<p>' . __("No events yet.", 'silentauction') . '</p>';
		echo '<a href="' . get_admin_url(null, 'admin.php')."?page={$eventsPageKey}&view=add\" class=\"{$linkClassString}\">"
			. __("Click here to get started.", 'silentauction') . '</a>';
	}	
	// otherwise
	else {
		echo '<p><a href="' . get_admin_url(null, 'admin.php')."?page={$eventsPageKey}\" class=\"{$linkClassString}\">"
			. __("Click here to activate an event.", 'silentauction') . '</a></p>';
	}
}

// Event summary / dashboard
else {
	$eventInfo = $SA_Tables-> events-> getByID( $currentEventID );
	if ( $eventInfo !== false ){
		echo '<p><h3>' . $eventInfo[ 'title' ] . '</h3></p>';
		
		$numBidders = $SA_Tables-> bidders-> getCount( $currentEventID );
		$numItems = $SA_Tables-> items-> getCount( $currentEventID, 1 );		
		
		?>
<table class="form-table">
		
	<tr>
	<th scope="row"><label for="numBidders">Number of Bidders</label></th>
	<td><?php echo $numBidders; ?></td>
	</tr>
	
	<tr>
	<th scope="row"><label for="numBidders">Number of Items</label></th>
	<td><?php echo $numItems; ?></td>
	</tr>
	
	
</table>
		<?php
	}
}
?>

</div>