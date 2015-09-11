<?php 
function sa_tab_nav(){
?>
<div id="sa-tabs-wrap">
	<a class="nav-tab nav-tab-active" href="<?php echo get_admin_url(null, 'admin.php')."?page=sa-admin-main"?>" >Home</a>
	<a class="nav-tab nav-tab-inactive" href="<?php echo get_admin_url(null, 'admin.php')."?page=sa-items"?>" >Items</a>
	<a class="nav-tab nav-tab-inactive" href="<?php echo get_admin_url(null, 'admin.php')."?page=sa-bidders"?>" >Bidders</a>
</div>
<?php
}
?>