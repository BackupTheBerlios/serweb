<?php
/**
 *	Edit domain aliases, customer and admins
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: domain_edit.php,v 1.5 2007/02/14 16:36:39 kozlik Exp $
 *	@package    serweb
 *	@subpackage admin_pages
 */ 

$_data_layer_required_methods=array();
$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('multidomain');

$_required_apu = array('apu_domain'); 


/** include all others necessary files */
require "prepend.php";

$perm->check("admin,hostmaster");
if (!$sess->is_registered('sess_admin')) {$sess->register('sess_admin'); $sess_admin=1;}

$smarty->assign('admin_select_url', 'admin_select.php');

$page_attributes['selected_tab']="list_of_domains.php";

$do	= new apu_domain();
$do->set_opt('redirect_on_update', 'list_of_domains.php');
$do->set_opt('redirect_on_disable', 'list_of_domains.php');
$do->set_opt('redirect_on_delete', 'list_of_domains.php');

$do->set_opt('prohibited_domain_names', $config->prohibited_domains);

$controler->add_apu($do);
$controler->add_reqired_javascript('functions.js');
$controler->set_template_name('a_domain_edit.tpl');
$controler->start();


?>
