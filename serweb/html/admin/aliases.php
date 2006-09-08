<?
/*
 * $Id: aliases.php,v 1.7 2006/09/08 12:27:32 kozlik Exp $
 */

$_data_layer_required_methods=array();

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('uri');

$_required_apu = array('apu_aliases'); 

require "prepend.php";

$perm->check("admin");


$al	= new apu_aliases();

$page_attributes['selected_tab']="users.php";

$smarty->assign('uname', $controler->user_id->get_username());
$smarty->assign('domain',$config->domain);

$al->set_opt('get_all_uids_for_uri', true);
$al->set_opt('allow_edit', true);
if (!$perm->have_perm('hostmaster')){
	if (false === $dom = $_SESSION['auth']->get_administrated_domains()) $dom = array();
	$al->set_opt('allowed_domains', $dom);
}


$controler->add_apu($al);
$controler->add_reqired_javascript('functions.js');
$controler->set_template_name('a_aliases.tpl');
$controler->start();


?>
