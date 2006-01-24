<?php
/*
 * $Id: admin_assign.php,v 1.1 2006/01/24 11:40:54 kozlik Exp $
 */ 

$_data_layer_required_methods=array();
$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('multidomain', 'privileges');

$_required_apu = array('apu_domain_admin', 'apu_privileges'); 

require "prepend.php";

$perm->check("admin,hostmaster");


$da	= new apu_domain_admin();
$da->set_opt('get_list_of_domains', false);

$pr	= new apu_privileges();


$controler->change_url_for_reload("domain_edit.php");
$controler->add_apu($da);
$controler->add_apu($pr);
$controler->start();


?>

