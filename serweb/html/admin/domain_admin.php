<?php
/*
 * $Id: domain_admin.php,v 1.2 2005/12/22 12:54:32 kozlik Exp $
 */

$_data_layer_required_methods=array();

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('multidomain');

$_required_apu = array('apu_domain_admin'); 

require "prepend.php";

$perm->check("admin,hostmaster");


$da	= new apu_domain_admin();

$page_attributes['selected_tab']="list_of_admins.php";

$smarty->assign('uname', $controler->user_id->uname);
$smarty->assign('domain',$config->domain);

$da->set_opt('redirect_on_update', 'list_of_admins.php');

$controler->add_apu($da);
$controler->do_not_check_perms_of_admin();
$controler->set_template_name('a_domain_admin.tpl');
$controler->start();

?>
