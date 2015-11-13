<?php
function sa_tab_nav_page( $pageKey, $title ){
	return array( 'title' => $title,
		'active' => ( $_REQUEST['page'] == $pageKey ) );
}

function sa_tab_nav_section( $sectionID, $title ){
	return array( 'title' => $title,
			'active' => ( isset( $_GET['section'] ) && $_GET['section'] == $sectionID ) );
}

function sa_tab_nav(){
	global $SA_Tables;
	$currentEventID = get_option( 'sa-current-event', '' );
	
	$pages = array();
	$pages[ 'sa-admin-main' ] = sa_tab_nav_page( 'sa-admin-main', "Home" );
	
	if ( $currentEventID != '' ){
		$sections = $SA_Tables-> itemSections-> getAll( $currentEventID );
	} else {
		$sections = array();
	}
	foreach ( $sections as $s ){
		$pages[ 'sa-items&section=' . $s[ 'ID' ] ]
			= sa_tab_nav_section( $s[ 'ID' ], $s[ 'title' ] );		
	}
	
	$pages[ 'sa-bidders' ] = sa_tab_nav_page( 'sa-bidders', "Bidders" );
	$pages[ 'sa-import' ] = sa_tab_nav_page( 'sa-import', "Import" );
	$pages[ 'sa-export' ] = sa_tab_nav_page( 'sa-export', "Export" );
	
?>
<div id="sa-tabs-wrap">
	<?php foreach( $pages as $pageKey => $page ): ?>
	<?php $tabClass = ( $page[ 'active' ] ) ? 'nav-tab-active' : 'nav-tab-inactive'; ?>
	<a class="nav-tab <?php echo $tabClass; ?>" href="<?php echo get_admin_url(null, 'admin.php')."?page=".$pageKey?>" ><?php echo $page[ 'title' ]; ?></a>
	<?php endforeach; ?>
</div>
<?php
}
?>