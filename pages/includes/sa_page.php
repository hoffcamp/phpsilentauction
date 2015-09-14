<?php 
function sa_heading( $subtitle ){
	global $SA_Tables;
	
	$currentEventID = get_option( 'sa-current-event', '' );
	$title = __( "Silent Auction", 'silentauction' );	
	if ( $currentEventID != '' ){
		$eventInfo = $SA_Tables-> events-> getByID( $currentEventID );
		if ( $eventInfo !== false ){
			$title = $eventInfo[ 'title' ];
		}
	}
	?>
	<h1><?php echo $title; ?></h1>
	<p><?php sa_tab_nav(); ?></p>
	<p><h1><?php echo $subtitle; ?></h1></p>	
	<?php
}
?>