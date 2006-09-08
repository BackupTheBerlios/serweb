<?
/*
 * $Id: admin_privileges.php,v 1.15 2006/09/08 12:27:32 kozlik Exp $
 */

$_data_layer_required_methods=array();

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('privileges');

$_required_apu = array('apu_privileges'); 

require "prepend.php";

$perm->check("admin,hostmaster");


$pr	= new apu_privileges();

$page_attributes['selected_tab']="list_of_admins.php";

$smarty->assign('uname', $controler->user_id->get_username());
$smarty->assign('domain',$config->domain);

$pr->set_opt('redirect_on_update', 'list_of_admins.php');


$controler->add_apu($pr);
$controler->set_template_name('a_admin_privileges.tpl');
$controler->start();

?>
