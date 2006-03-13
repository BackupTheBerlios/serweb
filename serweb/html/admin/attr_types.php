<?php
/*
 * $Id: attr_types.php,v 1.1 2006/03/13 15:34:05 kozlik Exp $
 */ 

$_data_layer_required_methods=array();
$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('attributes');

$_required_apu = array('apu_attr_types'); 


require "prepend.php";

$perm->check("admin,hostmaster");

$at	= new apu_attr_types();

$controler->add_apu($at);
$controler->add_reqired_javascript('functions.js');
$controler->set_template_name('a_attr_types.tpl');
$controler->start();

?>
