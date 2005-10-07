<?
/*
 * $Id: admin_privileges.php,v 1.13 2005/10/07 13:09:52 kozlik Exp $
 */

$_data_layer_required_methods=array();

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Pre_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('privileges');

$_required_apu = array('apu_privileges'); 

require "prepend.php";

$perm->check("admin,change_priv");


$pr	= new apu_privileges();

$page_attributes['selected_tab']="list_of_admins.php";

$smarty->assign('uname', $controler->user_id->uname);
$smarty->assign('domain',$config->domain);

$pr->set_opt('redirect_on_update', 'list_of_admins.php');
/* Privilege 'hostmaster' can assign only user, who has this privilege. 
 * Also in not multidomain config isn't privilege 'hostmaster' used.
 */
if (!$perm->have_perm('hostmaster') or !$config->multidomain) $pr->set_opt('disabled_privileges', array('hostmaster'));


if (!$config->multidomain) $controler->do_not_check_perms_of_admin();
$controler->add_apu($pr);
$controler->set_template_name('a_admin_privileges.tpl');
$controler->start();

?>
