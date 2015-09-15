<?php
function sa_tab_nav(){
	$pages = array(
		'sa-admin-main' => "Home",
		'sa-items' => "Auction Items",
		'sa-bidders' => "Bidders",
		'sa-import' => "Import",
		'sa-export' => "Export"
	);
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