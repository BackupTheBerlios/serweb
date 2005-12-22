<?
/*
 * $Id: acl.php,v 1.18 2005/12/22 12:54:32 kozlik Exp $
 */

$_data_layer_required_methods=array();

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('acl');

$_required_apu = array('apu_acl'); 

require "prepend.php";

$perm->check("admin");


$acl	= new apu_acl();

$page_attributes['selected_tab']="users.php";

$smarty->assign('uname', $controler->user_id->uname);
$smarty->assign('domain',$config->domain);

$acl->set_opt('allow_edit', true);
$acl->set_opt('redirect_on_update', 'users.php');



$controler->add_apu($acl);
$controler->set_template_name('a_acl.tpl');
$controler->start();

?>
