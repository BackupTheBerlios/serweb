<?
/*
 * $Id: accounting.php,v 1.1 2004/08/25 10:19:48 kozlik Exp $
 */ 

$_data_layer_required_methods=array('get_user_real_name');

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_apu = array('apu_accounting'); 

require "prepend.php";

if ($controler->come_from_admin_interface){
	/* script is called from admin interface, load page attributes of admin interface */
	require ("../admin/page_attributes.php");
	$page_attributes['selected_tab']="users.php";
}
else{
	$page_attributes['user_name'] = $data->get_user_real_name($serweb_auth, $controler->errors);
}

$acc=new apu_accounting();


$smarty->assign('url_admin', $sess->url($config->admin_pages_path."users.php?kvrk=".uniqid("")));

$controler->add_apu($acc);
$controler->set_template_name('u_accounting.tpl');
$controler->start();

?>