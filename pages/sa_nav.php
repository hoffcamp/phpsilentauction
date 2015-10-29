<?php
function sa_tab_nav(){
	global $SA_Tables;
	$currentEventID = get_option( 'sa-current-event', '' );
	
	$pages = array();
	$pages[ 'sa-admin-main' ] = "Home";	
	
	if ( $currentEventID != '' ){
		$sections = $SA_Tables-> itemSections-> getAll( $currentEventID );
	} else {
		$sections = array();
	}
	foreach ( $sections as $s ){
		$pages[ 'sa-items&section=' . $s[ 'ID' ] ] = $s[ 'title' ];
	}
	
	$pages[ 'sa-bidders' ] = "Bidders";
	$pages[ 'sa-import' ] = "Import";
	$pages[ 'sa-export' ] = "Export";	
	
?>
<div id="sa-tabs-wrap">
	<?php foreach( $pages as $pageKey => $pageTitle ): ?>
	<?php $tabClass = ( $_REQUEST['page'] == $pageKey ) ? 'nav-tab-active' : 'nav-tab-inactive'; ?>
	<a class="nav-tab <?php echo $tabClass; ?>" href="<?php echo get_admin_url(null, 'admin.php')."?page=".$pageKey?>" ><?php echo $pageTitle; ?></a>
	<?php endforeach; ?>
</div>
<?php
}
?>