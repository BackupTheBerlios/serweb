<?
/*
 * $Id: users.php,v 1.26 2005/10/31 16:35:20 kozlik Exp $
 */ 

$_data_layer_required_methods=array();
$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Pre_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('subscribers');

$_required_apu = array('apu_subscribers'); 


require "prepend.php";

$perm->check("admin");
if (!$sess->is_registered('sess_admin')) {$sess->register('sess_admin'); $sess_admin=1;}

if (isset($_GET['m_acl_updated'])){
	$controler->add_message(array(
		'short' => $lang_str['msg_acl_updated_s'],
		'long'  => $lang_str['msg_acl_updated_l']));
}

if (isset($_GET['m_user_registered'])){
	$controler->add_message(array(
		'short' => $lang_str['msg_user_registered_s'],
		'long'  => $lang_str['msg_user_registered_l']));
}


$sc	= new apu_subscribers();

$smarty->assign('domain',$config->domain);
$smarty->assign('xxl_support', isModuleLoaded('xxl'));
$smarty->assign('multidomain', $config->multidomain);

$sc->set_opt('use_chk_onlineonly', true);
$sc->set_opt('get_user_aliases', true);
$sc->set_opt('sess_seed', 0);
if ($config->multidomain){
	if (!$perm->have_perm('hostmaster')) $sc->set_opt('only_from_administrated_domains', true);
}
else
	$sc->set_opt('only_from_same_domain', true);

$controler->add_apu($sc);
$controler->add_reqired_javascript('functions.js');
$controler->set_template_name('a_users.tpl');
$controler->start();


?>
