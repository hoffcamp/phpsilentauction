<?php
global $SA_Tables;
global $SA_AdminLinks;

?>
<div class="wrap">
<h1><?php _e( "Silent Auction", 'silentauction' ); ?></h1>

<?php
// no event is currently set
if ( get_option( 'sa-current-event', '' ) == '' ){
	$eventList = $SA_Tables-> events-> getAll();
	$eventsPageKey = 'sa-events';
	$linkClassString = 'page-title-action';
	
	// ... because there are no events
	if ( count( $eventList ) == 0 ){
		echo '<p>' . __("No events yet.", 'silentauction') . '</p>';
		echo '<a href="' . get_admin_url(null, 'admin.php')."?page={$eventsPageKey}&crud-view=add\" class=\"{$linkClassString}\">"
			. __("Click here to get started.", 'silentauction') . '</a>';
	}	
	// otherwise
	else {
		echo '<a href="' . get_admin_url(null, 'admin.php')."?page={$eventsPageKey}\" class=\"{$linkClassString}\">"
			. __("Click here to activate an event.", 'silentauction') . '</a>';
	}
}

else {
	
}
?>

</div>