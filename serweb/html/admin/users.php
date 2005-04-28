<?
/*
 * $Id: users.php,v 1.21 2005/04/28 14:29:09 kozlik Exp $
 */ 

$_data_layer_required_methods=array();
$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Pre_Auth",
						   "perm" => "phplib_Perm");

$_required_apu = array('apu_subscribers', 'apu_xxl_proxy_select'); 

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
$smarty->assign('xxl_support', $config->enable_XXL);

$sc->set_opt('use_chk_onlineonly', true);
$sc->set_opt('only_from_same_domain', true);
$sc->set_opt('get_user_aliases', true);
$sc->set_opt('sess_seed', 0);

if ($config->enable_XXL) {
	$xxl = new apu_xxl_proxy_select();

	$xxl->set_opt('smarty_form', 'xxl_form');
	$xxl->set_opt('form_submit', array('type' => 'button',
									   'text' => 'Change'));
	$controler->add_apu($xxl);
}

$controler->add_apu($sc);
$controler->add_reqired_javascript('functions.js');
$controler->set_template_name('a_users.tpl');
$controler->start();


?>
