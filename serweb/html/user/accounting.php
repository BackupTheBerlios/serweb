<?
/*
 * $Id: accounting.php,v 1.5 2006/01/12 13:49:26 kozlik Exp $
 */ 

$_data_layer_required_methods=array();

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('accounting');

$_required_apu = array('apu_accounting'); 

require "prepend.php";

if ($controler->come_from_admin_interface){
	/* script is called from admin interface, load page attributes of admin interface */
	require ("../admin/page_attributes.php");
	$page_attributes['selected_tab']="users.php";
}
else{
	$page_attributes['user_name'] = get_user_real_name($serweb_auth);
}

$acc			= new apu_accounting();

$acc->set_opt('display_incoming', false);
$acc->set_opt('display_outgoing', true);
$acc->set_opt('display_missed', false);

/* if you doesn't need this, disable it for perfonmance reasons */
$acc->set_opt('get_user_status', true);
/* if you doesn't need this, disable it for perfonmance reasons */
$acc->set_opt('get_phonebook_names', true);

$acc->set_opt('smarty_result', 'acc');


//create copy of some options from config in order to sensitive options will not accessible via templates
$cfg=new stdclass();
$cfg->enable_ctd            = $config->enable_ctd;

$smarty->assign_by_ref("config", $cfg);
$smarty->assign('url_admin', $sess->url($config->admin_pages_path."users.php?kvrk=".uniqid("")));

$controler->add_apu($acc);
$controler->set_template_name('u_accounting.tpl');
$controler->start();

?>
