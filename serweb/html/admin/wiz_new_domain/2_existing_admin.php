<?
/*
 * $Id: 2_existing_admin.php,v 1.1 2005/11/03 11:02:08 kozlik Exp $
 */ 

$_data_layer_required_methods=array();
$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Pre_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('subscribers');

$_required_apu = array('apu_subscribers'); 


require "prepend.php";

$perm->check("admin");

$page_attributes['title'] .= " - ".$lang_str['step']." 2/3";

$sc	= new apu_subscribers();

$smarty->assign('xxl_support', isModuleLoaded('xxl'));
$smarty->assign('finish_url', '3_finish.php?da_assign=1&pr_set_admin_privilege=1');

$sc->set_opt('use_chk_adminsonly', true);
$sc->set_opt('def_chk_adminsonly', true);
$sc->set_opt('sess_seed', 1);
if ($config->multidomain and !$perm->have_perm('hostmaster')) $sc->set_opt('only_from_administrated_domains', true);

$controler->add_apu($sc);
$controler->set_template_name('a_wiz_new_domain/2_existing_admin.tpl');
$controler->start();


?>