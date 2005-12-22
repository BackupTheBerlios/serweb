<?
/*
 * $Id: aliases.php,v 1.5 2005/12/22 12:54:32 kozlik Exp $
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

$smarty->assign('uname', $controler->user_id->uname);
$smarty->assign('domain',$config->domain);

$al->set_opt('allow_edit', true);


$controler->add_apu($al);
$controler->add_reqired_javascript('functions.js');
$controler->set_template_name('a_aliases.tpl');
$controler->start();


?>
