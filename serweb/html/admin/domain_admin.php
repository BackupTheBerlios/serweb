<?php
/**
 *	Assign/unassign domains to admin
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: domain_admin.php,v 1.4 2007/02/14 16:36:39 kozlik Exp $
 *	@package    serweb
 *	@subpackage admin_pages
 */ 

$_data_layer_required_methods=array();

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('multidomain');

$_required_apu = array('apu_domain_admin'); 

/** include all others necessary files */
require "prepend.php";

$perm->check("admin,hostmaster");


$da	= new apu_domain_admin();

$page_attributes['selected_tab']="list_of_admins.php";

$smarty->assign('uname', $controler->user_id->get_username());
$smarty->assign('domain',$config->domain);

$da->set_opt('redirect_on_update', 'list_of_admins.php');

$controler->add_apu($da);
$controler->do_not_check_perms_of_admin();
$controler->set_template_name('a_domain_admin.tpl');
$controler->start();

?>
