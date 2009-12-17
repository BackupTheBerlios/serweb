<?php
/**
 *	Edit access control lists of users
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: acl.php,v 1.21 2009/12/17 12:11:56 kozlik Exp $
 *	@package    serweb
 *	@subpackage admin_pages
 */ 

$_data_layer_required_methods=array();

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('acl');

$_required_apu = array('apu_acl'); 

/** include all others necessary files */
require "prepend.php";

$perm->check("admin");


$acl	= new apu_acl();

$page_attributes['selected_tab']="users.php";

$smarty->assign('uname', $controler->user_id->get_username());
$smarty->assign('domain',$controler->user_id->get_domainname());

$acl->set_opt('allow_edit', true);
$acl->set_opt('redirect_on_update', 'users.php');



$controler->add_apu($acl);
$controler->set_template_name('a_acl.tpl');
$controler->start();

?>
