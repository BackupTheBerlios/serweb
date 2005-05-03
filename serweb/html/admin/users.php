<?
/*
 * $Id: users.php,v 1.23 2005/05/03 11:15:03 kozlik Exp $
 */ 

$_data_layer_required_methods=array();
$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Pre_Auth",
						   "perm" => "phplib_Perm");

$_required_apu = array('apu_subscribers'); 

require "prepend.php";

$perm->check("admin");
if (!$sess->is_registered('sess_admin')) {$sess->register('sess_admin'); $sess_admin=1;}


if (isset($_GET['m_acl_updated'])){
	$controler->add_message(array(
		'short' => $lang_str['msg_acl_updated_s'],
		'long'  => $lang_str['msg_acl_updated_l']));
}


$sc	= new apu_subscribers();

$smarty->assign('domain',$config->domain);
$smarty->assign('xxl_support', isModuleLoaded('xxl'));

$sc->set_opt('use_chk_onlineonly', true);
$sc->set_opt('only_from_same_domain', true);
$sc->set_opt('get_user_aliases', true);
$sc->set_opt('sess_seed', 0);

$controler->add_apu($sc);
$controler->add_reqired_javascript('functions.js');
$controler->set_template_name('a_users.tpl');
$controler->start();


?>
