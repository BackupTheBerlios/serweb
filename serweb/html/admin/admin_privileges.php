<?
/*
 * $Id: admin_privileges.php,v 1.11 2005/04/21 15:09:46 kozlik Exp $
 */

$_data_layer_required_methods=array();

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Pre_Auth",
						   "perm" => "phplib_Perm");

$_required_apu = array('apu_privileges'); 

require "prepend.php";

$perm->check("admin,change_priv");


$pr	= new apu_privileges();

$page_attributes['selected_tab']="list_of_admins.php";

$smarty->assign('uname', $controler->user_id->uname);
$smarty->assign('domain',$config->domain);

$pr->set_opt('redirect_on_update', 'list_of_admins.php');

$controler->add_apu($pr);
$controler->do_not_check_perms_of_admin();
$controler->set_template_name('a_admin_privileges.tpl');
$controler->start();

?>
