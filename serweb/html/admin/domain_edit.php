<?php
/*
 * $Id: domain_edit.php,v 1.2 2005/12/22 12:54:32 kozlik Exp $
 */ 

$_data_layer_required_methods=array();
$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('multidomain');

$_required_apu = array('apu_domain'); 


require "prepend.php";

$perm->check("admin,hostmaster");
if (!$sess->is_registered('sess_admin')) {$sess->register('sess_admin'); $sess_admin=1;}

$page_attributes['selected_tab']="list_of_domains.php";

$do	= new apu_domain();
$do->set_opt('redirect_on_update', 'list_of_domains.php');
$do->set_opt('redirect_on_disable', 'list_of_domains.php');
$do->set_opt('redirect_on_delete', 'list_of_domains.php');

$controler->add_apu($do);
$controler->add_reqired_javascript('functions.js');
$controler->set_template_name('a_domain_edit.tpl');
$controler->start();


?>
