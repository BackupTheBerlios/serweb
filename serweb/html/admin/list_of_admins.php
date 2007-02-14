<?php
/**
 *	Display list of admins
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: list_of_admins.php,v 1.18 2007/02/14 16:36:39 kozlik Exp $
 *	@package    serweb
 *	@subpackage admin_pages
 */ 

$_data_layer_required_methods=array();
$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('subscribers');

$_required_apu = array('apu_subscribers', 'apu_sorter'); 


/** include all others necessary files */
require "prepend.php";

$perm->check("admin,hostmaster");
if (!$sess->is_registered('sess_admin')) {$sess->register('sess_admin'); $sess_admin=1;}

if (isset($_GET['m_pr_updated'])){
	$controler->add_message(array(
		'short' => $lang_str['msg_privileges_updated_s'],
		'long'  => $lang_str['msg_privileges_updated_l']));
}


$sc	= new apu_subscribers();
$sr = new apu_sorter();

$sc->set_sorter($sr);

$smarty->assign('domain',$config->domain);
$smarty->assign('xxl_support', isModuleLoaded('xxl'));
$smarty->assign('change_domain_admin', $config->multidomain and $perm->have_perm('hostmaster'));

$sc->set_opt('use_chk_adminsonly', true);
$sc->set_opt('def_chk_adminsonly', true);
$sc->set_opt('sess_seed', 1);
if (!$perm->have_perm('hostmaster')) $sc->set_opt('only_from_administrated_domains', true);

$controler->add_apu($sc);
$controler->add_apu($sr);
$controler->set_template_name('a_list_of_admins.tpl');
$controler->start();


?>
