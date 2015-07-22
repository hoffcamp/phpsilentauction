<?php
global $SA_Tables;
global $SA_AdminLinks;

?>
<div class="wrap">
<h1><?php _e( "Silent Auction", 'silentauction' ); ?></h1>
	
<?php
// no event is currently set
$currentEventID = get_option( 'sa-current-event', '' );
if ( $currentEventID == '' ){
	$eventList = $SA_Tables-> events-> getAll();
	$eventsPageKey = 'sa-events';
	$linkClassString = 'page-title-action';
	
	// ... because there are no events
	if ( count( $eventList ) == 0 ){
		echo '<p>' . __("No events yet.", 'silentauction') . '</p>';
		echo '<a href="' . get_admin_url(null, 'admin.php')."?page={$eventsPageKey}&view=add\" class=\"{$linkClassString}\">"
			. __("Click here to get started.", 'silentauction') . '</a>';
	}	
	// otherwise
	else {
		echo '<a href="' . get_admin_url(null, 'admin.php')."?page={$eventsPageKey}\" class=\"{$linkClassString}\">"
			. __("Click here to activate an event.", 'silentauction') . '</a>';
	}
}

// Event summary / dashboard
else {
	$eventInfo = $SA_Tables-> events-> getByID( $currentEventID );
	if ( $eventInfo !== false ){
		echo '<p><h3>' . $eventInfo[ 'title' ] . '</h3></p>';
	}
}
?>

</div>